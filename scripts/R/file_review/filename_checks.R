######### Checking filenames in db vs actual files
## A script to confirm that all files referenced in the files db table exist inside storage/app/public/rawdata.
source('init.R')

files_db <- tbl(con, 'files') %>% collect()

check <- list.files('../../storage/app/public/rawfiles/')

files_db <- files_db %>%
    mutate(found = name %in% check)

names_to_check <- files_db %>%
    filter(found == FALSE) %>%

    ## ignore station 10 for now, as we don't know which station it is!
    filter(station_id != 10) %>%
    select('name')

## names_to_check should be empty.
# If there are any filenames inside names_to_check, it means there is a record in the files table without a corresponding file inside storage/app/public/rawdata.
