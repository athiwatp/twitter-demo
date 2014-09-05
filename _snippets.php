FIX ERROR FOR SPACE

.text
.user
	screen_name:"theponpons"
	profile_image_url_https:
	"https://pbs.twimg.com/profile_images/506762209083396096/tjRzxg_I_normal.jpeg"




Data Storage
Store all user searches using cookies and database.
● Check if the search result is already in the database.
● Check if the stored results are older than 1 hour. If so, update the database so we
have the latest results.

API Calls
Call an API of your choice to retrieve the coordinates of the city (Google, Bing, Open Street, ...)
Call the Twitter API and only return results within 50km of the city. Only store tweets that are returned with coordinate data.


Build a menu that allows users to access previous searches (using the cookie from Data Storage) and allow the user to perform searches based on this history.


High­Level Documentation: Create high­level documentation where you explain your project.
