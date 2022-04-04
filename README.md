## Step 1: Create Project in Google Console

## Step 2: Enabling APIs and creating credentials

### Go to APIs and Services and enable Google Classroom API

### APIs and Services > Credentials > OAuth client ID

    > Application Type: Web Application
    > Name: Give it a name
    > Authorized JavaScript origins: This defines the base URL from where the google OAuth authentication will start
    > Authorized redirect URIs: This defines the callback route where the route will be redirected after logging in

### APIs and Services > OAuth consent screen

    > App Name: Give it a name
    > User Support Email: Provide the console account email
    > Authorized redirect URIs: provide the base url
    > Authorized domains: if you want to restrict domain call

Save and Continue

### Scopes

    Here you have to add all the resource scopes which you may access in future. Ex: Class Room all scopes will let you access all the API calls. If you don't enable it we will get Permission error.

## Step 3: Downloading the OAuth Credential

    Download the OAuth credential and #paste it in your laravel project #public folder [or your base folder if public changes]

## Step 4: install Google Client

    > Link: https://developers.google.com/classroom/quickstart/php?hl=en&authuser=1
    > Install: composer require google/apiclient

## Step 5: Create routes

    > See web.php

## Step 6: Create Controller

    > See GoogleController
