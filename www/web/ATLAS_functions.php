<?php

function database_connect(): SQLite3
{
    return new SQLite3("./tables/dump.sqlite", SQLITE3_OPEN_READONLY);
}
