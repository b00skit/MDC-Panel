<?php

    /*
    |------------------------------------------------------------------------------------
    |                                MDC PANEL CONSTANTS
    |------------------------------------------------------------------------------------
    |
    |	 -- READ THIS, IT'S IMPORTANT
    |
    |    This constants file is the heart of the entire application, here you can modify
    |    pretty much the entire website through one file. Through this file you are
    |    effectively able to edit the website settings without having to crawl through
    |    multiple different files.
    |
    |-----------------------------------------------------------------------------------
    |                                   LICENSE INFO
    |-----------------------------------------------------------------------------------
    |
    |    GNU GENERAL PUBLIC LICENSE
    |    Version 3, 29 June 2007
    |
    |    Copyright (c) 2020 MDC PANEL
    |
    |    Permission is hereby granted, free of charge, to any person obtaining a copy
    |    of this software and associated documentation files (the "Software"), to deal
    |    in the Software without restriction, including without limitation the rights
    |    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    |    copies of the Software, and to permit persons to whom the Software is
    |    furnished to do so, subject to the following conditions:
    |
    |    The above copyright notice and this permission notice shall be included in all
    |    copies or substantial portions of the Software.
    |
    |    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    |    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    |    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    |    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    |    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    |    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
    |    SOFTWARE.
    |    
    |-----------------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MAIN WEBSITE [@ENV]
    | ------------------------
    | ! THESE CONSTANTS ARE ALL ENVIROMENT CONSTANTS AND SHOULD NOT BE
    |   COMMITTED THROUGH THROUGH A MERGE REQUEST.
    | + Here you can change and edit stuff such as the website name, footer
    |   information and other general website stuff.
    |--------------------------------------------------------------------------
    |
    */

    const SITE_LIVE = true;
    const SITE_NAME = 'MDC Panel';
    const SITE_VERSION = '2.4.17';
    const SITE_LOGO = '/images/MDC-Panel.svg';
    const SITE_FAVICON = '/images/MDC-Panel-Favicon.svg';
    const SITE_IMAGE = '/images/MDC-Panel-OG.png';
    const SITE_DESCRIPTION = 'MDC Panel - Multi-functional tools, generators, and resources for official government use.';
    const SITE_DISCORD_CONTACT = 'Biscuit#0001';

    /*
    |--------------------------------------------------------------------------
    | URL / LINK
    | ------------------------
    | + Here you are able to modify all external links displayed on the
    |   website.
    |--------------------------------------------------------------------------
    |
    */

    const URL_GITHUB = 'https://github.com/biscuitgtaw/MDC-Panel';
    const URL_DISCORD = 'https://discord.gg/rxfYd23TNz';
    const URL_MDC = 'https://mdc.gta.world';
    const URL_CAD = 'https://cad.gta.world';
    const URL_LSPD = 'https://lspd.gta.world';
    const URL_LSSD = 'https://lssd.gta.world';
    const URL_LSFD = 'https://lsfd.gta.world';
    const URL_LSDA = 'https://lsda.gta.world';
    const URL_SADCR = 'https://sadcr.gta.world';
    const URL_SFM = 'https://sfm.gta.world';
    const URL_SASP = 'https://saparks.gta.world';
    const URL_PENAL_CODE = 'https://forum.gta.world/en/topic/78852-san-andreas-penal-code/';
    const URL_BAIL_SCHEDULE = 'https://docs.google.com/spreadsheets/d/1qm04NZm-HEy-vdW2liNWcdok7eXFGty_rO-dA1H_k0g/';
    const URL_BBCODERIP = 'https://rebb.booskit.dev/';
    const URL_ST3FAN = 'https://st3fannl.nl/gtaw/';
    const URL_DEPA = 'https://forum.gta.world/en/topic/30297-drug-enforcement-prevention-act-of-2020-depa/';

    /*
    |--------------------------------------------------------------------------
    | SPECIAL NOTIFICATIONS
    | ------------------------
    | + Want to put up a global notification? You can do that! Simply
    |   define a name of the cookie followed by the notification!
    |--------------------------------------------------------------------------
    |
    */

    const SPECIAL_NOTIFICATION_COOKIE = 'end-of-life';
	const SPECIAL_NOTIFICATION_MESSAGE = 'The MDC Panel will be updated on the <b>1st of September 2025</b>. We will be releasing the new MDC Panel+ on this domain, this old version of the MDC will be moved to a seperate domain and will no-longer be supported. This does not mean much for you, but once you visit the domain you will see a new sleek and cool website!';

    /*
    |--------------------------------------------------------------------------
    | FACTION SETTINGS
    | ------------------------
    | + Here you are able to modify, add or remove factions from the website.
    |--------------------------------------------------------------------------
    |
    */

    const FACTIONS = [
		"LSPD" => ["name" => "Los Santos Police Department", "ranks" => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17]],
		"LSSD" => ["name" => "Los Santos Sheriff's Department", "ranks" => [18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29]],
		"SFM" => ["name" => "State Fire Marshal", "ranks" => [30, 31, 32, 33, 34, 35, 36, 37]],
		"SAPR" => ["name" => "San Andreas State Parks", "ranks" => [38, 39, 40, 41, 42, 43, 44, 45, 46, 47]],
		"LSPE" => ["name" => "Los Santos Parking Enforcement", "ranks" => [48, 49, 50]],
		"SAAA" => ["name" => "San Andreas Aviation Administration", "ranks" => [51, 52, 53]],
		"LSDA" => ["name" => "Los Santos District Attorney's Office", "ranks" => [55, 56, 57, 58]],
		"JSA" => ["name" => "Judiciary of San Andreas", "ranks" => [59, 60, 61, 62, 63, 64]],
		"SADOC" => ["name" => "San Andreas Department of Corrections", "ranks" => [65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78]],
	];

    const FACTIONS_LEO = ["LSPD", "LSSD", "SFM", "SAPR", "SADOC", "SAAA"];
    const FACTIONS_PARKING_ENFORCEMENT = ["LSPE", "LSPD", "LSSD", "SAPR", "SFM"];

    /*
    |--------------------------------------------------------------------------
    | PENAL CODE SETTINGS
    | ------------------------
    | + Here you are able to define penal code categories.
    |--------------------------------------------------------------------------
    |
    */

    const CHARGES_DRUG = [131, 601, 602, 603, 604, 605, 606];
    const CHARGES_TRAFFIC = [401, 402, 403, 404, 405, 406, 407, 408, 409, 410, 411, 412, 413, 414, 415, 416, 417, 418, 419, 420, 421, 422, 423, 424, 425, 426, 428];
    const CHARGES_DISABLED = [000, 423];

    /*
    |--------------------------------------------------------------------------
    | BAIL / BOND SETTINGS
    | ------------------------
    | + Here you are able to define the bail and bond conditions of the website.
    |--------------------------------------------------------------------------
    |
    */

    const BOND_PERCENTAGE = 10;

    /*
    |--------------------------------------------------------------------------
    | ILLEGAL PARKING SETTINGS
    | ------------------------
    | + Here you are able to setup the settings for the illegal parking
    | controller.
    |--------------------------------------------------------------------------
    |
    */

    const ILLEGAL_PARKING_REASONS_VS = [0]; // Vehicle Status
    const ILLEGAL_PARKING_REASONS_PR = [1, 2, 3, 4, 5, 18, 19, 20]; // Parking Related
    const ILLEGAL_PARKING_REASONS_OS = [6, 7, 8, 9, 10, 11, 12, 13]; // Obstruction
    const ILLEGAL_PARKING_REASONS_SW = [14, 15, 16, 17]; // Sidewalk

