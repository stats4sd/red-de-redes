# import mysql database driver, call it as "mysql"
import mysql.connector as mysql

# import database connection configuration from file "dbConfig.py", call it as "config"
import dbConfig as config

# import column names settings from file "listColumnsName.py", call it as "columns_name"
import listColumnsName as columns_name

# import popular Python-based data analysis library "pandas", call it as "pd"
import pandas as pd

# import numpy library, call it as "np"
import numpy as np

# import unit conversion custom library from file "convertorUnits.py", call it as "convertor"
import convertorUnits as convertor

# import date time library, call it as "datetime"
from datetime import datetime

# import system library, to access system-specific parameters and functions
import sys


# get parameter values from command line arguments
# including data file full path, user selected station id, 
# user selected unit for temperature, pressure, wind, rainfall,
# uploader id, observeration id
path = sys.argv[1]
station_id = sys.argv[2]
selected_unit_temp = sys.argv[3]
selected_unit_pres = sys.argv[4]
selected_unit_wind = sys.argv[5]
selected_unit_rain = sys.argv[6]
uploader_id = sys.argv[7]
is_windows = sys.argv[8]
newObservation_id = sys.argv[9]



# function to process the uploaded data file
def openFile():


    ##################
    ##### DAVIS ######
    ##################


    # if file extension is "txt", assume it is davis data file
    if(path[len(path)-3 : ] == "txt"):

        # read a comma-separated values (csv) file into data frame
        # specify na_values for additional strings to recognize as NA/NaN, including --.-, --, ---, ------
        # specify separater as Tab key
        # specify row 0 and row 1 to as column headers
        # do not specify low_memory = False, internally process the file in chunks, 
        # resulting in lower memory use while parsing, but possibly mixed type inference
        df = pd.read_csv(path, na_values=['--.-', '--', '---', '------'], sep="\t", header=[0,1])


        # option to print out all columns in a data frame
        # pd.set_option('display.max_columns', None)


        # define new_column_names as an empty array
        new_columns_names = []


        # for each column in data frame
        for i in df.columns:


            # if row 0 column name is empty
            if(i[0][0:7]=="Unnamed"):


                # include only the second column name
                # trim row 1 column name, set it as new_column_name
                #
                # ISSUE: why it is not necessary to replace spaces to underscores here?
                #
                # SUGGESTION: same as below, to replace all spaces to underscore character _ in new_column_name
                new_column_name = i[1].strip()


                # replace all spaces to underscore character _ in new_column_name
                new_column_name = new_column_name.replace(' ', '_')


            # if row 0 column name is not empty
            else:


                # get one column from data frame
                i = list(i)


                # trim column name in row 0
                i[0] = i[0].strip()


                # trim column name in row 1
                i[1] = i[1].strip()


                # construct new column name = [row 0 trimmed column name] + "_" + [row 1 trimmed column name]
                new_column_name = i[0] + '_' + i[1]


                # replace all spaces to underscore character _ in new_column_name
                new_column_name = new_column_name.replace(' ', '_')


            # add new_column_name to new_column_names array
            new_columns_names.append(new_column_name)


        # pass the new columns_name to the dataframe
        df.columns = new_columns_names
 

        # rename the column name for davis into column name for the database
        #
        # ISSUE: column names are defined with UPPERCASE and lowercase, what if column name in data file not exactly matched 
        # with defined column name?
        #
        # SUGGESTION: to eliminate case difference for renaming column
        # to define column names in UPPERCASE, to change column name to UPPERCASE before calling .rename()
        # 
        # UPDATE: Bolivia team agreed not to change column headers
        df = df.rename(columns=columns_name.list_columns_davis_text)
       

        # define date_time as a new array for processing measurement date time
        date_time = []


        # handle all fecha_hora, time data in data frame
        for fecha_hora, time in zip(df.fecha_hora, df.time):


            # tokenize fecha_hora into an array called date, by using "/" as delimiter
            # davis date format is DD/MM/YY
            date = fecha_hora.split('/')


            # tokenize time into an array called hour, by using ":" as delimiter
            # davis time format is HH:MI
            #
            # ISSUE: davis station must be configured to use 24 hours format
            # it will have same time for both AM and PM if it is configured to use 12 hours format
            # e.g. 1:15 and 13:15 are both presented as 1:15
            #
            # SUGGESTION: To define a standard procedure for adding a new met station, make sure met station is configured as 24 hours format
            #
            # TODO: Is it possible to detect this in data file and display error message in front end?
            hour = time.split(':')


            # construct a new date_time as "20" + YY + MM + DD + HH + MI
            #
            # ISSUE: each portion is 2 digits except hour portion can be 1 digit
            # there is no leaading zero added for hour, we will have result with different length
            # e.g. 03/02/21 0:15  => 20210203015
            # e.g. 03/02/21 10:15 => 202102031015
            #
            # SUGGESTION: 
            # 1. ensure each portion is 2 digits by adding leading zero
            # 2. add hour portion as "00", to form a complete date time format with fixed length
            # e.g. 03/02/21 0:15  => 20210203001500
            # e.g. 03/02/21 10:15 => 20210203101500
            #
            # SOLUTION: use datetime() function to generate date time in a standardized format
            date_time.append(str(datetime(int('20' + date[2]), int(date[1]), int(date[0]), int(hour[0]), int(hour[1]))))


        # pass the standard format datestamp into fecha_hora
        df.fecha_hora = date_time


        # drop columns that are not matched with any pre-defined column header
        # to keep columns defined in list_columns_name (database columns), remove all other columns in data frame
        df = df[df.columns.intersection(columns_name.list_columns_name)]


        # drop rows with missing value / NaN in all columns
        # For recording purpose, it is recommended to keep the record even it has date and time only
        # So that we know the met station has performed measurement for this date time
        df = df.dropna(how='all', subset=columns_name.list_columns_davis_to_drop)


        # add the station_id column
        df['id_station'] = station_id


        # add the uploader_id column
        df['uploader_id'] = uploader_id


        # add the observation_id column
        if newObservation_id=='null' :
            df['observation_id'] = None
        else:
            df['observation_id'] = newObservation_id


        # convert data
        # unit conversion
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


        # change non-missing values NaN to None before adding to MySQL database, because MySQL does not recognize NaN
        df = df.where((pd.notnull(df)), None)


    ##################
    ##### CHINAS #####
    ##################


    # if file extension is not "txt", assume it is chinas data file
    #
    # ISSUE: all non-txt will be considered as chinas data file, e.g. csv, doc, xls, ppt, jpg, png, zip
    #
    # SUGGESTION: only check whether file extension is "csv". if it is "csv", then assume it is chinas
    # we can use file extension as a first checking, and use column headers as a second checking
    else:

        # read a comma-separated values (csv) file into data
        # specify na_values for additional strings to recognize as NA/NaN, including --.-, --.-, --, --, ---, ---, ------, ------
        # specify separater as comma ,
        # do not specify which row is header, default row 0 as column names
        # specify low_memory = False, to ensure no mixed types, the entire file is read into a single DataFrame
        data = pd.read_csv(path, na_values=['--.-',' --.-', '--',' --', '---',' ---', '------', ' ------'], sep=",", low_memory=False)


        # option to print out all columns in a data frame
        # pd.set_option('display.max_columns', None)


        # set data into a data frame
        df = pd.DataFrame(data)


        # remove extra space in columns name
        # trim spaces at the beginning and at the end of all column names
        # column name handling is more complicated for davis because column name in two rows
        # column name handling is simpler for chinas because column name in one row only
        df.columns = df.columns.str.rstrip()


        # add id_station column
        df['id_station'] = station_id


        # add the uploader_id column
        df['uploader_id'] = uploader_id


        # add the observation_id column
        if newObservation_id=='null' :
            df['observation_id'] = None
        else:
            df['observation_id'] = newObservation_id


        # drop columns not necessary
        # remove column "No." (record number) from data frame as it is not necessary
        df = df.drop(['No.'], axis=1)


        # replace columns name
        # rename the column name for chinas station into column name for the database
        #
        # ISSUE: column names are defined with UPPERCASE and lowercase, what if column name in data file not exactly matched 
        # with defined column name?
        #
        # SUGGESTION: to eliminate case difference for renaming column
        # to define column names in UPPERCASE, to change column name to UPPERCASE before calling .rename()
        #
        # UPDATE: Bolivia team agreed not to change column headers
        df = df.rename(columns=columns_name.list_columns_chinas_csv)


        # define date_time as a new array for processing measurement date time
        # Chinas can have the time in 12 hours (am, pm) or 24 hours
        date_time = []


        # handle all fecha_hora in data frame, date time are in one column for chinas data file
        for fecha_hora in df.fecha_hora:

            # trim date time column
            date = fecha_hora.strip()

            # tokenize date time by delimiter space
            # separate date time into date portion and time potion
            date = date.split(' ')

            # if there are 3 portions in date array
            # e.g. 2021-02-03 10:15:00 AM
            if len(date)==3:

                # construct time portion and AM/PM portion
                hours = date[1]+" "+date[2]

                # convert time from 12 hour format to 24 hour format
                hours = convertor.convert24(hours) 

            # if there are not 3 portions in date array
            # i.e. only 2 portions in date array
            # e.g. 2021-02-03 10:15:00
            else:

                # time portion is already in 24 hour format
                # construct time from time portion only
                hours = date[1]

            # tokenize time by delimiter :
            # separate time into hour, minute, second portion

            hours = hours.split(':')

            # tokenize date by delimiter /
            # separate date into day, month, year portion
            date_splited = date[0].split('/')

            # construct a new date_time as "20" + YY + MM + DD + HH + MI
            #
            # ISSUE: each portion is 2 digits, but hour portion can be 1 digit. Minute and second portion to be confirmed
            # there is no leaading zero added for hour, we will have result with different length
            # e.g. 03/02/2021 0:15:00  => 2021020301500
            # e.g. 03/02/2021 10:15:00 => 20210203101500
            #
            # SUGGESTION: 
            # 1. ensure each portion is 2 digits by adding leading zero
            # 2. add hour portion as "00", to form a complete date time format with fixed length
            # e.g. 03/02/2021 0:15:00  => 20210203001500
            # e.g. 03/02/2021 10:15:00 => 20210203101500
            #
            # SOLUTION: use datetime() function to generate date time in a standardized format
            date_time.append(str(datetime(int(date_splited[2]), int(date_splited[1]), int(date_splited[0]), int(hours[0]), int(hours[1]), int(hours[2]))))


        # pass the standard format datestamp into fecha_hora
        df.fecha_hora = date_time


        # drop rows with missing value / NaN in all columns
        # For recording purpose, it is recommended to keep the record even it has date and time only
        # So that we know the met station has performed measurement for this date time
        df = df.dropna(how='all', subset=columns_name.list_columns_chinas_to_drop)


        # convert data
        # unit conversion
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


        # change non-missing values NaN to None before adding to MySQL database, because MySQL does not recognize NaN
        df = df.where((pd.notnull(df)), None)


    # replace all Nan values to '999' temporary to avoid MySQL error (for Windows only)
    # P.S. Linux does not need this, but Windows needs this to avoid MySQL error
    if is_windows == "1":
        df = df.replace(np.nan, '999', regex=True)


    # add system date time to created_at, updated_at
    systemDateTime = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    df['created_at'] = systemDateTime
    df['updated_at'] = systemDateTime


    # return data frame after data processing
    return df


# connects to MySQL database
conn = mysql.connect(**config.dbConfig)


# create cursor object to execute SQL statements
cursor = conn.cursor()


# try block to test a block of code for errors
try:


    # call openFile() to get and handle data from uploaded data file
    data = openFile()


    # convert all columns in data frame into a list
    cols = data.columns.tolist()


    # prepare a comma separated string for all column names
    cols = '`,`'.join(cols)


    # print message to the screen
    print('data is uploading')


    # define data_value as an empty array
    data_value = []


    # iterate each row in data frome
    for i, row in data.iterrows():


        # construct INSERT SQL statement
        sql = f"INSERT INTO `data_template` (`{cols}`) VALUES (" + "%s,"*(len(row)-1) + "%s)"


        # append INSERT SQL statement to data_value array for later execution
        # prepare INSERT SQL statement VALUES clause with data of multiple records
        data_value.append(tuple(row))


    # no error handling if SQL excption occured (e.g. unique constraint violated due to two records with same date time)
    cursor.executemany(sql, data_value)


    # commit changes to database
    conn.commit()


# except block to handle the error
except mysql.Error as err:


    # print message to the screen
    # ISSUE: error message will not be showed in front end, error message will not be written to log file
    # that means, nobody knows when error occurred
    print(f'Failed to upload data: {err}')


# else block to define a block of code to be executed if no errors were raised
else:
    print('data uploaded')


# close MySQL database connection
conn.close
