
#Agenda

FuckShitUp 0.1 - Multi Vulnerabilities Scanner

Basically, FSU is bunch of tools written in PHP-CLI. Using build-in functions, you are able to grab url's using search engines - and so, dork for interesting files and full path disclosures. Using list of url's, scanner will look for Cross Site Scripting, Remote File Inclusion, SQL Injection and Local File Inclusion vulnerabilities. It is able to perform mass bruteforce attacks for specific range of hosts, or bruteforce ssh with specific username taken from FPD. Whenever something interesting will be found, like vulnerability or broken auth credentials, data will be saved in .txt files - just like url's, and any other files. FSU is based on PHP and text files, it's still under construction so i am aware of any potential bugs. Principle of operation is simple.

More url's -> more vuln's.
For educational purposes only.

#Intro

- Data grabbing:
 - URL's (geturl/massurl) -> (scan)
 - Configs, Databases, SQLi's (dork)
 - Full Path Disclosures / Users (fpds) -> (brutefpds)
 - Top websites info (top)
  
- Massive scanning
 - XSS, SQLi, LFI, RFI (scan)
 - FTP, SSH, DB's, IMAP (multibruter)
 - Accurate SSH bruteforce (brutefpds)

#Plan

- Web Apps
 - Grab url's via 'geturl' or 'massurl' (massurl requires list of tags as file)
 - Scan url's parameters for vulns with 'scan'

- Servers
 - Pick target, get ip range
 - Scan for services on each IP and bruteforce with 'multibruter'
 - Grab full path disclosures, and so linux usernames
 - Perform SSH bruteforce for specific user with 'brutefpds'

- Info grabbing
 - Use 'dork' for automatic dorking
 - Use 'fpds' for full path disclosure grabbing
 - Use 'search' for searching someone in ur databases
 - Use 'top' for scanning all top websites of specific nation

- Others
 - 'Stat' shows actual statistics and informations
 - 'Show' display specific file
 - 'Clear' and 'filter' - remove duplicates, remove blacklisted url's

#Others

MultiBrtuer requirements (php5):
 - php5-mysql - for mysql connections
 - php5-pgsql - for postgresql connections
 - libssh2-php - for ssh connections
 - php5-sybase - for mssql connections
 - php5-imap - for imap connections

Screens:
 - http://i.imgur.com/WKEbVGQ.png
 - http://i.imgur.com/PJtYWQk.png
 - http://i.imgur.com/o8fyyLQ.png
 - http://i.imgur.com/WY8ncBx.png
 - http://i.imgur.com/cmoTcPY.png

TODO:
 - Fix problems with grabbing large amount of url's
 - More search engines
 - SQL Injector
 - RFI shell uploader
 - FSU is not secure as it should be
