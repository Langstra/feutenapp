# Feutenapp
Feutenapplicatie voor registratie van misdragende feutjes

# API
### authenticate
Login on an account. Accounts are association-binded
#### params
username

password

#### returns
##### on success
32 character long token
##### on failure
false

### add_noob
Add a noob to your association
#### params
name

img_url

token

### add_points
Register a point for a noob of your association
#### params
array

 * noob_ids

reason_text

reason_file

amount

token

### get_noob
Retrieve info of a certain noob of your association.
#### params
token
#### returns
##### on success
* id
* name
* img_url

##### on failure
false

### get_noobs
Get a list of noobs of your association
#### params
token
#### returns
##### on success
array
* array
  * id
  * name
  * img_url

##### on failure
false

### get_points
Retrieve the data of all points registered for a certain noob of your association.
#### params
noob_id

token
### returns
array
* amount
* reason_text
* reason_file
* timestamp (seconds since UNIX Epoch)

### get_total_points
Get the total amount of points registered with your association.
#### params
token
#### return
##### on success
total number of points for the association
##### on failure
false

### get_point_list
Get a list of data of all points registered with your association.
#### params
token
#### return
##### on success
array
  * noob_id => amount

##### on failure
false

### get_point_days
Get the days on which points were registered with your association.
#### params
token
#### return
##### on success
array of days on which points were registered
##### on failure
false

### add_board_member
Add a board member with login to your association.
#### params
username

password

token

### edit_password
Edit your own password.
#### params
current_password

new_password

token

### register_association
Register a new association.
#### params
association_name

username

password
#### return
##### on success
token
##### on failure
false

Plugins used
============
``
cordova plugin add org.apache.cordova.camera org.apache.cordova.file org.apache.cordova.file-transfer https://github.com/pwlin/cordova-plugin-file-opener2
``


Build the app for release
=========================
```
ionic build android
cordova build --release android
jarsigner -verbose -sigalg SHA1withRSA -digestalg SHA1 -keystore f3-release-ley.keystore platforms/android/build/outputs/apk/android-armv7-release-unsigned.apk fris_en_feutig
zipalign -v 4 platforms/android/build/outputs/apk/android-armv7-release-unsigned.apk fris_en_feutig.apk
```