library('RMariaDB')
dotenv::load_dot_env("../../.env")

con <- dbConnect(RMySQL::MySQL(),
                 dbname = Sys.getenv("DB_DATABASE"),
                 host = Sys.getenv("DB_HOST"),
                 port = as.integer(Sys.getenv("DB_PORT")),
                 user = Sys.getenv("DB_USERNAME"),
                 password = Sys.getenv("DB_PASSWORD"),
                 timezone_out = "America/Anguilla",
                 time_zone = "-04:00"
)


dbSendQuery(con, "set character set 'utf8mb4'")
