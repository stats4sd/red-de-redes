import mysql.connector as mysql
import dbConfig as config
import listColumnsName as columns_name
import pandas as pd
import convertorUnits as convertor
from datetime import datetime
import sys

path = sys.argv[1]
station_id = sys.argv[2]
selected_unit_temp = sys.argv[3]
selected_unit_pres = sys.argv[4]
selected_unit_wind = sys.argv[5]
selected_unit_rain = sys.argv[6]
uploader_id = sys.argv[7]
newObservation_id = sys.argv[8]


def openFile():

    if(path[len(path)-3 : ] == "txt"):

        df = pd.read_csv(path, na_values=['--.-', '--', '---', '------'], sep="\t", header=[0,1])

        new_columns_names = []
        for i in df.columns:

            if(i[0][0:7]=="Unnamed"):
                #include only the second column name
                new_column_name = i[1].strip()
            else:
                i = list(i)
                i[0] = i[0].strip()
                i[1] = i[1].strip()
                new_column_name = i[0] + '_' + i[1]
                new_column_name = new_column_name.replace(' ', '_')

            new_columns_names.append(new_column_name)

        #pass the new columns_name to the dataframe
        df.columns = new_columns_names

        #rename the column name for davis station into column name for the database
        df = df.rename(columns=columns_name.list_columns_davis_text)


        #create the timestamp for uploading into database
        date_time = []
        for fecha_hora, time in zip(df.fecha_hora, df.time):

            date = fecha_hora.split('/')
            hour = time.split(':')
            date_time.append(str(datetime(int('20' + date[2]), int(date[1]), int(date[0]), int(hour[0]), int(hour[1]))))

        #pass the right datestamp into fecha_hora
        df.fecha_hora = date_time
        #drop columns that are not into the database
        df = df[df.columns.intersection(columns_name.list_columns_name)]

        # drop rows with missing value / NaN in any column
        df = df.dropna(how='all', subset=columns_name.list_columns_davis_to_drop)
        #add the station_id column
        df['station_id'] = station_id
        #add the uploader_id column
        df['uploader_id'] = uploader_id
        #add the observation_id column
        if newObservation_id=='null' :
            df['observation_id'] = None
        else:
            df['observation_id'] = newObservation_id

        #convert data
        if selected_unit_pres != "hpa":
            print('converting data presion in inhg or mmhg to hpa')
            df = convertor.convertDataInhgOrMmhgToHpa(df, selected_unit_pres, 1)

        if selected_unit_rain != "mm":
            print('converting data rain in inch to mm')
            df = convertor.convertDataInchToMm(df, selected_unit_rain, 1)

        if selected_unit_wind != "m/s":
            print('converting data wind in km or mph to ms')
            df = convertor.convertDatakmOrMToMs(df, selected_unit_wind, 1)

        if selected_unit_temp != "ºC":
            print('converting data temperature in F to C')
            df = convertor.convertDataFtoC(df, selected_unit_temp, 1)

        df = df.where((pd.notnull(df)), None)

    else:

        data = pd.read_csv(path, na_values=['--.-',' --.-', '--',' --', '---',' ---', '------', ' ------'], sep=",", low_memory=False)

        df = pd.DataFrame(data)
        # remove extra space in columns name
        df.columns = df.columns.str.rstrip()

        # add station_id column
        df['station_id'] = station_id

        #add the uploader_id column
        df['uploader_id'] = uploader_id

        #add the observation_id column
        if newObservation_id=='null' :
            df['observation_id'] = None
        else:
            df['observation_id'] = newObservation_id

        #drop columns not necessary
        df = df.drop(['No.'], axis=1)

        # replace columns name
        df = df.rename(columns=columns_name.list_columns_chinas_csv)

        #create the timestamp for uploading into database
        #Chinas can have the time in 12 hours (am, pm) or 24 hours
        date_time = []
        for fecha_hora in df.fecha_hora:
            date = fecha_hora.strip()
            date = date.split(' ')
            if len(date)==3:
                hours = date[1]+" "+date[2]
                hours = convertor.convert24(hours)
            else:
                hours = date[1]
            hours = hours.split(':')
            date_splited = date[0].split('/')
            date_time.append(str(datetime(int(date_splited[2]), int(date_splited[1]), int(date_splited[0]), int(hours[0]), int(hours[1]), int(hours[2]))))


        #pass the right datestamp into fecha_hora
        df.fecha_hora = date_time

        # drop rows with missing value / NaN in any column
        df = df.dropna(how='all', subset=columns_name.list_columns_chinas_to_drop)
        #convert data
        if selected_unit_pres != "hpa":
            print('converting data presion in inhg or mmhg to hpa')
            df = convertor.convertDataInhgOrMmhgToHpa(df, selected_unit_pres, 0)

        if selected_unit_rain != "mm":
            print('converting data rain in inch to mm')
            df = convertor.convertDataInchToMm(df, selected_unit_rain, 0)

        if selected_unit_wind != "m/s":
            print('converting data wind in km or mph to ms')
            df = convertor.convertDatakmOrMToMs(df, selected_unit_wind, 0)

        if selected_unit_temp != "ºC":
            print('converting data temperature in F to C')
            df = convertor.convertDataFtoC(df, selected_unit_temp, 0)

        df = df.where((pd.notnull(df)), None)


    return df

# connects to database
conn = mysql.connect(**config.dbConfig)
cursor = conn.cursor()

try:
    data = openFile()
    cols = data.columns.tolist()
    cols = '`,`'.join(cols)

    print('data is uploading')
    data_value = []
    for i, row in data.iterrows():
        sql = f"INSERT INTO `data_template` (`{cols}`) VALUES (" + "%s,"*(len(row)-1) + "%s)"
        data_value.append(tuple(row))

    cursor.executemany(sql, data_value)
    conn.commit()

except mysql.Error as err:
    print(f'Failed to upload data: {err}')

else:
    print('data uploaded')

conn.close
