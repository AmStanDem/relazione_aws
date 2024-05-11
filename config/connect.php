<?php
const HOSTNAME = 'localhost';
const USERNAME = 'root';
const PASSWORD = '';
const DATABASE = 'relazione_aws';

const CONN = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);

if (CONN->connect_errno)
{
    echo 'Db connection error: '.CONN->connect_error;
}