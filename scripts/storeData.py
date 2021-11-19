import mysql.connector as mysql
import dbConfig as config
import listColumnsName as columns_name
import sys

uploader_id = sys.argv[1]
# connects to database
conn = mysql.connect(**config.dbConfig)

cursor = conn.cursor()
cursorSelect = conn.cursor();

try:

    counterSuccess = 0;
    counterFailure = 0;

    # get pre-defined column name list
    cols = '`,`'.join(columns_name.list_columns_name)

    # get all ID for this uploader_id in table data_template
    sqlSelect = f"SELECT id FROM `data_template` WHERE `uploader_id`='{uploader_id}' ORDER BY id;"

    cursorSelect.execute(sqlSelect)

    resultSelect = cursorSelect.fetchall()

    print("data is inserting")

    for row in resultSelect:
        sqlInsert = f"INSERT INTO `data` (`{cols}`) SELECT `{cols}` FROM `data_template` WHERE `uploader_id`='{uploader_id}' AND `id` = {row[0]};"

        try:
            cursor.execute(sqlInsert)
            counterSuccess = counterSuccess + 1;

        except mysql.Error as dberr:
            print(f'Failed to upload data: {dberr}')
            counterFailure = counterFailure + 1;

    print(f'{counterSuccess} record(s) inserted into data table successfully')
    print(f'{counterFailure} record(s) cannot be inserted into data table')

    conn.commit()

except mysql.Error as err:
    print(f'Failed to upload data: {err}')

else:
    sqlDelete = f"DELETE FROM `data_template` WHERE `uploader_id`='{uploader_id}';"
    cursor.execute(sqlDelete)
    conn.commit()
    print('data inserted')
    conn.close