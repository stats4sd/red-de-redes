#!/usr/bin/python3
from mysql.connector import MySQLConnection, Error
import sys
import dbConfig as config
import pandas as pd



path = sys.argv[1] + '/storage/app/public/data/'
query = sys.argv[2]
name_file = sys.argv[3]
queries = query.split(';')
sheet_names = sys.argv[4]
sheet_names = sheet_names.split(',')

print(queries);
try:
	con = MySQLConnection(**config.dbConfig)
	cursor = con.cursor()
	dfs = {} 
	i = 0
	for query in queries:
		cursor.execute(query)
		df = pd.DataFrame(cursor, columns=[i[0] for i in cursor.description])
		dfs[sheet_names[i]] = df
		i += 1
	
	writer = pd.ExcelWriter(path + name_file, engine='xlsxwriter')

	for sheet_name in dfs.keys():
		dfs[sheet_name].to_excel(writer, sheet_name=sheet_name, index=False)
		
	writer.save()
	writer.close()
	con.commit()

	
except Error as e:

	print('Error:', e)

finally:

	print('Done!')
	cursor.close()
	con.close()




