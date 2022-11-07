# Red de Redes Platform
This platform is being developed as an inter-institutional collaborative netowork as part of the Collaborative Crop Research Program (CCRP).

https://weatherstations.stats4sd.org/

## Purpose
The main objective of the platform is to provide a service that allows the participant partners the following:

**Meteorological data storage and retrieval**

1.	Ability to store weather data collected through meteorological stations (met stations). 
    
    Data is stored in the database of the platform and uploaded either by direct transmission by the met station through Meteobridge or by manual upload of data files by people in the field. There are different types of met stations and the formats may vary slightly.

    Data is collected every 15 minutes by the met stations and stored in that format.

2.	Find and retrieve data. Users will query the database to retrieve data. In most cases the data retrieved will be summarised data (daily, 10-day, monthly and annual periods) for one or more met stations. Sometimes raw data may be needed to be downloaded but this should be offered as a bespoke service.

    Data storage and retrieval needs to be designed in such a way that we make optimal use of storage space and server use. It may make more sense to run queries on summarised data rather than the original data, but this may not be easy.

3.	Derive indicators from the data stored. The data from the met stations can be used to calculate useful indices (e.g. evapotranspiration). These indices will be specified by our colleagues in the Red de Redes. The calculation of the indices may be done either through data management tools or in cases where the calculations may be more complex to program, using R libraries that already exist.

4.	The data stored should also be accessible to other platforms via APIs.

**Agronomic and soils data**

1.	Crop data is collected by our partners in the field through an ODK form. The variables were agreed by the members of the Red de Redes and needs to be stored in the platform.

2.	Soils data is also collected by the Red de Redes using the protocol of the Soils cross-cutting project and compatible with the Soils Platform. This link is important as an early example of integration of databases for agroecology. Other type of soils data that is expected to be gathered is data from soil lab analyses. There is a feedback loop attempted to let the soil lab that a soil sample has been send to them and for the researcher to receive the lab results.

3.	The main reason to have the crop and soils data in the same platform as the weather data is that the researchers will want to retrieve the three types of data based on location and time. The specific data structures will need to be discussed in detail, but we need to make sure that identifiers are created to allow this type of matching.

In the long term, the ability to store and match different types of data will be used to track agroecological transitions, particularly if we integrate data that comes from standardised tools like RHoMIS.

**Information services**

Projects are interested in using the data to generate information relevant to local agents (farmers, farmer organisations, local government, NGOs, etc). This will require the development of front ends that are suitable for those audiences. This is still only a statement of intention by our colleagues of the Red de Redes, and we will need to engage with them to make it specific enough to allow us to develop the functionality.

# Development
This platfrom is built using Laravel/PHP. The front-end is written in VueJS and the admin panel uses Backpack for Laravel.

## Setup Local Environment
1.	Clone repo: `git@github.com:stats4sd/red-de-redes.git`
2.	Copy `.env.example` as a new file and call it `.env`
3.	Update variables in `.env` file to match your local environment:
    1. Check APP_URL is correct
    2. Update DB_DATABASE (name of the local MySQL database to use), DB_USERNAME (local MySQL username) and DB_PASSWORD (local MySQL password)
4. Create a local MySQL database with the same name used in the `.env` file
5. Run the following setup commands in the root project folder:
```
composer install
php artisan key:generate
php artisan backpack:install
php artisan telescope:publish
npm install
npm run dev
cd scripts/R && Rscript -e "renv::restore()"
```
6. Migrate the database: `php aritsan migrate:fresh --seed` (or copy from the staging site)

## Note on Met Data
If you bring in any of the live met data records to your local environment, you will likely need to set your MySQL timezone to Bolivia time (GMT -5) to avoid getting unique constraint errors at the daylight-savings points for your local timezone e.g. if your local db is set to default (and your computer is set to UK time), then the timestamps in met_data will resolve to show 2 x records for station id = 4 on 2011-10-30 01:00:00. Timezones are weird... 
