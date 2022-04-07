# Python functions for unit conversions


# Function to convert time from 12 hour AM/PM format to 24 hour format
def convert24(str1): 
    # Checking if last two elements of time 
    # is AM and first two elements are 12 
    if str1[-4:] == "a.m." and str1[:2] == "12": 
        return "00" + str1[2:-5] 
          
    # remove the AM     
    elif str1[-4:] == "a.m.": 
        return str1[:-5] 
      
    # Checking if last two elements of time 
    # is PM and first two elements are 12    
    elif str1[-4:] == "p.m." and str1[:2] == "12": 
        return str1[:-5] 
          
    else: 
        # add 12 to hours and remove PM 
        return str(int(str1[:2]) + 12) + str1[2:8] 


# Function to convert temperature from Fahrenheit to Celsius
def convertFahrenheitToCelsius(value):
	
	temp_celsius = ((value-32)*5/9)
	return temp_celsius
	

# Function to convert pressure from inhg or mmhg to hpa
# TODO: return value from variable instead
def convertInhgOrMmhgToHpa(value, pression_unit):

	if pression_unit=="inhg":
		
		press_hpa = value*33.863886666667
		return value*33.863886666667

	elif pression_unit=="mmhg":
		
		press_hpa = value*1.3332239
		return value*1.3332239


# Function to convert speed from km/h or mph to m/s
def convertkmOrMToMs(value, veloc_viento_unit):

	if veloc_viento_unit=="km/h":
		
		veloc_viento_ms = value*0.277778
		return veloc_viento_ms

	elif veloc_viento_unit=="mph":
		
		veloc_viento_ms = value*0.44704
		return veloc_viento_ms
	

# Function to convert length from inch to mm
def convertInchToMm(value):

	rain_mm = value*25.4
	return rain_mm


# Function to convert data items from inch to mm in data frame
def convertDataInchToMm(dataframe, selected_unit_rain, davis):

	if(selected_unit_rain != "mm" and davis):

		dataframe['rain'] = convertInchToMm(dataframe['rain'])
		dataframe['lluvia_hora'] = convertInchToMm(dataframe['lluvia_hora'])

	if(selected_unit_rain != "mm" and not davis):

		dataframe['lluvia_hora'] = convertInchToMm(dataframe['lluvia_hora'])
		dataframe['lluvia_24_horas'] = convertInchToMm(dataframe['lluvia_24_horas'])
		dataframe['lluvia_semana'] = convertInchToMm(dataframe['lluvia_semana'])
		dataframe['lluvia_mes'] = convertInchToMm(dataframe['lluvia_mes'])
		dataframe['lluvia_total'] = convertInchToMm(dataframe['lluvia_total']);
	
	return dataframe


# Function to convert data items from km/h or mph to m/s in data frame
def convertDatakmOrMToMs(dataframe, selected_unit_wind, davis):

	if(selected_unit_wind!="m/s" and davis):

		dataframe['hi_speed'] = convertkmOrMToMs(dataframe['hi_speed'], selected_unit_wind)
	
	if(selected_unit_wind!="m/s" and not davis):				
				
		dataframe['velocidad_viento'] = convertkmOrMToMs(dataframe['velocidad_viento'], selected_unit_wind)
		dataframe['rafaga'] = convertkmOrMToMs(dataframe['rafaga'], selected_unit_wind)

	return dataframe


# Function to convert data items from inhg or mmhg to hpa in data frame
def convertDataInhgOrMmhgToHpa(dataframe, pression_unit, davis):
	
	if pression_unit != "hpa" and davis:
			
		dataframe['presion_relativa'] = convertInhgOrMmhgToHpa(dataframe['presion_relativa'], pression_unit)

	if pression_unit != "hpa" and not davis:
			
		dataframe['presion_relativa'] = convertInhgOrMmhgToHpa(dataframe['presion_relativa'], pression_unit)
		dataframe['presion_absoluta'] = convertInhgOrMmhgToHpa(dataframe['presion_absoluta'], pression_unit)

	return dataframe


# Function to convert data items from Fahrenheit to Celsius in data frame
def convertDataFtoC(dataframe, temp_unit, davis):
	
	if(temp_unit=='ºF' and davis):
		
		dataframe['temperatura_interna'] = convertFahrenheitToCelsius(dataframe['temperatura_interna']);
		dataframe['temperatura_externa'] = convertFahrenheitToCelsius(dataframe['temperatura_externa']);
		dataframe['punto_rocio'] = convertFahrenheitToCelsius(dataframe['punto_rocio']);
		dataframe['wind_chill'] = convertFahrenheitToCelsius(dataframe['wind_chill']);
		dataframe['hi_temp'] = convertFahrenheitToCelsius(dataframe['hi_temp']);
		dataframe['low_temp'] = convertFahrenheitToCelsius(dataframe['low_temp']);
			
	if(temp_unit=='ºF' and not davis):

		dataframe['sensacion_termica'] = convertFahrenheitToCelsius(dataframe['sensacion_termica']);
		dataframe['punto_rocio'] = convertFahrenheitToCelsius(dataframe['punto_rocio']);

	return dataframe

