# SURVEY PANEL WEBSITE
#### Video Demo:  https://youtu.be/6fp8tGjPisY
#### Description:

The project I have chosen as my final project for CS50 is a website I have been working on for about 5 months,
built to support a survey panel business serving the cannabis industry. The site is written in html, css, and php
for back-end communication with my SQL database, which I manage through phpMyAdmin. I am using Wordpress hosting
for my site, so much of the design is inherited through the theme I have selected. However, I have created a "child theme"
in order to have more control over the back-end design and functionality.

A working test version of the site can be found at [here](www.survey-buds.com). The [sign up/registration process](www.survey-buds.com/sign-up) can be tried out if desired.

## SURVEY PANELS 101

Survey panels are groups of individuals (consumers, professionals of a certain industry, sufferers of rare diseases, or some other
group of people sharing common characteristics) who have been recruited for the purpose of participating in research studies
in the form of online surveys. These individuals will opt-in to receive invitations to respond to these surveys
with the understanding that their participation will be compensated with either cash, gift cards, credits for a video game, or some
other type of incentive.

In order to join a survey panel, individuals must sign up to the panel by undergoing some type of screening process. This screening will
collect basic (i.e. demographic) information about the user, and if the survey panel is specialized in a certain type of "audience", it
may also also collect information about that individual's behaviors, attitudes, and preferences with regards to that specialty area. This
allows the managers of the survey panel to A) Validate that the individual is qualified to participate in the panel on the basis of
being a member of whatever type of audience the panel is meant to be comprised of, and B) assemble a set of characteristics of the individual
that can be used to "pre-screen" that person for future surveys whose respondents must meet certain criteria (i.e. of a certain age, females only,
residents of a certain zip code, etc.).

Once a survey panel is large and well-diversified enough, the panel managers can licence out access to clients who wish to conduct survey
research, specifying a certain price per complete (completed response to the survey offered), part of which is passed along to the panelists
in the form of an agreed-upon incentive.

## REQUIREMENTS FOR A WEB-BASED SURVEY PANEL

A website serving a survey panel operation must support several processes:
 - A process of signing up to the panel by going through a validation screener, as described above
 - A registration process, where contact information of the would-be panelist is given for the purposes of creating their account
 - A members-only area, accessed through a login form, where the panelist can track their incentive rewards, request payouts, see if any surveys are
    available for responding to, or perform other typical account-related tasks
 - A process for administrators to distribute survey invitations to panelists, usually as an email notification
 - A process for hosting survey questions on a series of webpages, with script to record their response data in a database

## DETAILS ABOUT THE SURVEY BUDS SURVEY PANEL

The survey panel I am in the process of building is designed to address consumer market research needs of the cannabis industry. Potential clients
of the company could be cannabis producers, dispensaries, or other intermediaries between the industry and my operation.

Panelists who are recruited to the Survey Buds panel are compensated for research participation in the form of Survey Buds Rewards Points. Each (client) survey
a panelist completes earns them a few hundred reward points. Once the panelist has accumulated at least 1,000 points, they can request to redeem their
points for cash (disbursed through PayPal) at a rate of $10.00 per 1,000 points. Their points then reset after redemption.

In order for someone to join thesurvey panel, they must go through a screening process (contained in the .php files with "sign-up" in the title)
that validates that they are at least 18, and that they use either thc- or cbd-containing cannabis products. Also, in order to qualify for the program,
neither them nor anyone in their household can be currently employed in the cannabis industry. Other checks are incorporated into the signup process
to ensure that the user is a benevolent potential research participant and not someone attempting to abuse the system to improperly receive reward payments.
These checks include running their IP address against those of existing panelists, and having them complete a logical check to help ensure that they do in
fact live in the zip code they have provided as the one they reside in.

Once the user has passed the screening process, they may register by choosing a username, entering their email address, and choosing and confirming a password.
Submiting this form adds the user to a 'panelists' table in the database, marked as INACTIVE, and sends them an e-mail message containing a link to validate
that they are in fact the owner of the email address provided. Following the link sets the panelist as ACTIVE in the database and allows them to log in to
their Survey Buds profile.

A panelist accesses their profile through a login form. The profile area contains the basic information they provided at registration (username, email address)
and contains links to forms that allow them to update this information if necessary, as well as change their password. Also housed in the profile area is
a description of the number of Survey Buds points they have accumulated, and how many more are needed before they can redeem them for cash. There is also a link
for them to trigger this redemption.

The website currently supports the fielding of a profile survey that collects additional user behavior with regards to cannabis consumption beyond what is
asked in during sign up. This includes the motivations for their use, whether or not they are a medical card holder, their preferred consumption forms, and
estimated use and purchase frequency and spend per purchase. Completing this profile is requisite for panel membership and is awarded with 200 reward points.

## DESCRIPTION OF FILES INCLUDED FOR THIS PROJECT

Most of the files contained in the project folder are .PHP files, but they contain HTML and CSS code as well. On the actual Survey Buds website, most of the
design is inherited from the chosen Wordpress theme, but still some custom styles are included.

### SIGN UP FOLDER

#### PAGE-SIGN-UP, PAGE-SIGN-UP-SCRIPT

The series of PHP files titles "sign-up" and numbered through 4 contain the user input forms for the sign-up screening process. The SIGN-UP page contains the
a form asking the user's gender, date of birth, and zip code. This form submits to PAGE-SIGN-UP-SCRIPT. The first action of the form handler script is to
establish a connection with the database. Then, as an initial validation step, it checks the user's IP addresses (which are contained in $_SERVER variables)
against those of existing panelists. If there is a match, the user is disqualified and sent to a page telling them they are ineligible. In this case, the
existing panelist account is also deleted and moved to a table containing all ineligible panelists. Otherwise, the informaton the user entered is added to a
SQL table called 'sign up', and they are directed to the next sign-up page and form.

#### PAGE-SIGN-UP-2/PAGE-SIGN-UP-2-SCRIPT THROUGH PAGE-SIGN-UP-4/PAGE-SIGN-UP-4-SCRIPT

The next three pages also contain forms for user input. The user selects which of a group of listed products they have purchased in the past 3 months. In order
to qualify, they must have selected a cannabis product. They also enter information about their employment and educational background, and whether they work in the
cannabis industry, which would disqualify them. The form handler script checks that the user's input does not disqualify them, and then adds values to the 'sign up'
table in the database. A final logical check is performed on sign-up page 4 where the user is asked the capital of the US state they live in. Their response is checked
against a table associating 41,000 US zip codes with their state capitals. This attempts to ensure the user is really who they say they are, and are not trying to
scam the survey program.

#### PAGE-TEST-SIGN-UP-2 AND PAGE-VERIFY

Once the user has qualified through the sign-up/screening process they may register. They select a username, enter their email, and choose a password. The HTML form
itself validates that the string entered for email is correctly formatted, and that the password is at least 8 characters. PHP script must validate that the two
password fields match. That being true, the password is hashed and a row is created in the 'panelist' table of the database with a panelist ID number, an email, a
password hash, and date the account was registed. There is also field in the row for their status as active or inactive, which defaults to inactive (0).

Next, the PHP mail() function is called to send the user a verification email. This message contains a link to PAGE-VERIFY. The URL link is formatted to contain $_GET
variables for the user's email address and the hash generated from the password they entered.

Clicking on the link in the user's inbox takes them to PAGE-VERIFY, where the $_GET variables are validated against the database tables. Successful validation results
in the 'active' field of the 'panelist' table being set to "1" for TRUE. Now the panelist is able to login to their profile.

### LOGIN FOLDER

#### PAGE-TEST-LOGIN

This is the login form that takes a user to their profile area. A database connection is established and the PHP `password_verify()`` function checks the password entered for that
email against the hash for that email in the 'panelist' table of the database. In the event the user forgot their password, their is a link to reset it (which relies on
basically the same script as the registration process). Successful password validation sends the user to their profile. Access to this page is secured through $_SESSION variables
that get set in the login form handler.

#### PAGE-MEMBER-PROFILE

This page displays the user's basic information and is the most design-centric of the project's pages. For this page I chose the CSS flexbox approach to achieve easier horizontal
alignment between the different HTML elements. This method allows you to set a parent element's display value to flex, and then set a width value for all of its child elements with
the flex attribute.

### SURVEYS FOLDER

#### PAGE-SURVEY-DISTRIBUTE

In order to initiate a survey, panelists receive an invite link in their email. The survey-distribute page facilitates this process. At this stage, I go into the actual script for
this page in a code editor, and enter the ID # of the survey to distribute, and paste in an array of panelist name's and email addresses who should receive the survey. When the page
loads in a browser, it displays what the email message will look like once received by panelists, and below that, a table of all the panelists who will receive the invite. I will
mention that the table is generated using a PHP for loop, declared in the middle of the HTML, to repeat a table row for each panelist in the array.

If everything looks good, I have two buttons at the bottom of the page, one for sending a test message to myself, and one for sending the invite out to panelists. The invite is sent
to panelists using another PHP for loop that repeatedly calls the mail() function.

For the sake of brevity, I have omitted from the collection of project files an admin login page that restricts access to this survey distribution tool to only administrators (i.e. myself).

#### PAGE-SURVEY-LOAD

The link from the survey invite message takes the user to this page, which actually does not display any HTML. It takes the user's email address and the survey ID in question (from $_GET variables
in the URL link), checks against the database that they are valid, fetches the panelist's ID #, and most importantly, stores this and the survey ID in session variables before directing the user
to the survey introduction. It then redirects to PAGE-SURVEY-INTRO.

#### PAGE-SURVEY-INTRO

This page fetches the survey's introduction text from the database and displays it to the user. The user clicks a "Start Survey" button whose action is the next page, SURVEY-SCRIPT.

#### PAGE-SURVEY-SCRIPT

This page is the form handler for survey intro and each survey page containing questions. There is branched logic that checks if the user is coming from the Intro page or from having submitted
survey questions.

If coming from the Intro page, the database is queried for the survey question data that should appear on the first survey page. This potentially returns multiple rows, so the rows are looped
through and the question data is stored in a $_SESSION variable which is an array. Question data includes the page number, question ID number, the question text ("What is your favorite dispensary, etc."),
question sub-text ("Please select all that apply, etc."), the question type (radio (select one), checkbox (select multiple), open, etc.) and the sequence on that page in which the questions should appear.

Still within the loop that reads the question data, the database is queried again for the answer data that relates to each question. This means the answer option ID and answer option text for each survey
question to be asked. This data is stored with the same $_SESSION array. Therefore, this becomes a multi dimension array; it is an array of arrays of question data, one of whose fields is an array of
answer option data (3-dimensional array).

The process is the same if the user is coming from having submitted a survey page form, except that first, the user's input (from $_POST variables) is recorded into the database. The page's script knows what
page a user is coming from thanks to a hidden input on all the forms that designates what the next page's page number is. If the database query returns any number of rows of question data for what should be the next
page, the user is now redirected to PAGE-SURVEY-FORM with a $_GET variable for the survey ID in the URL.

If the database is queried for the next page's question data and returns 0 rows, that means the survey has been completed. In this case, a response counter is incremented in the 'surveys' table of the
database to track how many people have taken the survey. The panelist's ID is added to another table, 'survey_completes', in case the admin needs to know who exactly took a particular survey. Finally,
the panelist is allocated the necessary incentive amount by updating the 'incentive' table in the database. The user then gets redirected to PAGE-SURVEY-END.

#### PAGE-SURVEY-FORM

This page contains the form HTML for displaying survey questions. The page uses a php "foreach" loop to go through each row of question data in the session array. Depending on the question type, different input
types are displayed when another foreach loop goes through all the answer option data.

It is important to note here how the form's element's names and values correspond to database fields. The `<label>` for all the questions is named after the question ID number.

* If the question is type radio (accepts only one selection) or type checkbox (accepts multiple selections), the <input> elements are also all named after the question ID, and their values are set to answer option ID numbers. After the <input> tag and before
  the closing </label> tag, the answer option text is displayed.

* If the question is type open (text field), the <input> is named for the question ID, and the value is left blank since there are no pre-defined answer options.

* Going back momentarily to PAGE-SURVEY-SCRIPT, the PHP code reads through all post variables submitted. This is performed in a foreach loop that reads the $name of each variable as well as its $value.

`foreach($_POST as $name => $value){}`

Answers are recorded by inserting into a database table called 'answers', which has fields for the survey ID, the panelist ID, the question ID, and the answer option ID. **A unique row is created for each response given,
even if multiple responses/options are selected for a single question.**

#### PAGE-SURVEY-END

The user is sent to this page, as mentioned earlier, when no question data is returned from the database when querying for what would be the next page's questions. It simply thanks the user for their
participation and confirms that they have been allocated their incentive reward.

### OTHER THINGS TO MENTION:

#### SECURITY:

Anywhere on the website's pages where TYPED user input is used to query the database, SQL injection attacks must be prevented. I have chosen to use prepared statements and parameter binding to
safeguard against this. This works as follows:

PHP `mysqli_prepare();` function accepts the database connection as its first argument. The second argument is the query string, with '?' characters acting as placeholders for variables (user input)
in the query. Example: `"SELECT panelist_id FROM panelists WHERE email = ? AND hash = ?"`. This tells the database to expect this query statement, and to read the values that stand in for the ?'s **only** as
values to search for and NOT as SQL commands themselves.

`mysqli_stmt_bind_param();` function accepts the prepared statement as its first argument, data types as its second argument, and variable names (for each ?) as its 3rd+ arguments.

Next, the variables (parameters) just need to be set, and then `mysqli_stmt_execute` is called to securely run the query.

If the result of the query needs to be used, then the functions `mysqli_stmt_store_result`, `mysqli_stmt_bind_result`, and `mysqli_stmt_fetch` are all called. `mysqli_stmt_num_rows` can get the number of rows
in the result set.

#### SURVEY PROGRAMMING:

Surveys are programmed by manually inserting data into the database. First, a row is created in the 'surveys' table, keyed on the survey_id field. Each row/survey has a title, a description, intro text, a start date
and end date, a boolean field for survey = open, the count of completed responses, and the incentive amount, which represents the number of rewards points to be given for completing the survey.

Next, question data is inserted into the 'questions' table, where each row contains the foreign key `survey_id`, the primary key `question_id`, the question text, the question subtext, the question type, the boolean field
for if the question is required, the question sequence (order), and the page of the survey it should appear on.

Answer option data is then inserted into the `answer_options` table, containing the foreign key `survey_id`, the foreign key `question_id`, the primary key `answer_option_id`, the answer option text, and the answer option
sequence. This table therefore will have a row for each possible answer option for each question of each survey.

#### SURVEY RESPONSE DATA:

Two tables get updated based on survey responses. The first is the `answers` table described earlier. Its columns are `survey_id`, `question_id`, `answer_option_id`, `panelist_id` (all foreign keys),
and the primary key `answer_id`.

The second is `survey_completes`, also described earlier. It gets updated on successful completion of a survey. It has columns for the `survey_id` and `panelist_id` foreign keys, the `survey_complete_id` primary key,
and a datetime column for when the survey was completed.




