JBFJ Events
=============

By Bryce Flory

Simple Events plugin.

Version: 1.9.4

=============

Feature additions coming soon:
- Currently feature complete

=============

Template Tag Use
-------------
Override plugin template by creating a custom template file in your child theme.
Use `<?php jbfj_events(); ?>` in your template file to call Events output. 

Override event output by creating a custom content-events.php file in your child theme.

Shortcode Use
-------------
Shortcode can be used like so: `[jbfj_events]`

Shortcode accepts one parameter to limit the number of events being displayed: `[jbfj_events max_posts=X]`

=============
Change Log:

1.9.4: February 21, 2015

 - template path name fix

1.9.3: February 12, 2015

 - Code Removal: Removed template check that was overriding previous, and correct, template check

1.9.2: January 21, 2015

 - Bug fix: error when no time entered on new event

1.9.1: January 20, 2015

 - Updated default template to include new custom message

1.9: January 20, 2015

 - Added custom message option for "No Events"

 - Fixed orderby AGAIN. Time was not being properly accounted for. Time now inputs to DB as UNIX time, making it properly sortable.

1.8.4: October 6, 2014

 - Refactored template to extrapolate output into an editable file. This is overrideable in child themes

1.8.3: September 25, 2014

 - Fixed orderby. Now correctly orders first by date, then by time

1.8.2: September 23, 2014

 - Template tag hotfix

1.8.1: September 12, 2014

 - Added shortcode jbfj_events
 - Shortcode has one parameter: max_posts

1.8: May 13, 2014

 - Added custom classing to widget output

1.7.1: May 1, 2014

 - Removed page title from new function

1.7: May 1, 2014

 - Added optional function for outputting events in any template.

1.6.2: March 19, 2014

 - Adjusted custom query to correctly compare event date/time against current date/time
 - Slightly streamlined code used to save to DB

1.6.1: March 19, 2014

 - Fixed widget output to remove events when their date and time has passed

1.6: March 18, 2014

 - Added Widget to display upcoming events. Includes settings for number of events to show, show date, time, venue, address, see all link
 - Added address field to Post Type meta box
 - Included address in default template

1.5: March 13, 2014

 - Added Event Duplication
 - Added Event Date to Quick Edit box
 
1.4: January 22, 2014

 - Added sorting by time

1.3.2: January 13, 2014

 - Added current page selected for events template to settings page

1.3.1: January 8, 2014

 - Added featured image ability to default page template

1.3: January 2, 2014

 - Added Settings page to select which page to use the template on.
 - Corrected the loop to correctly output "No Events"

1.2.2: December 19, 2013

 - Added default state for no current events

1.2.1: December 13, 2013

 - Fixed broken file paths to work with new naming convention
 
1.0: December 11, 2013

 - Initial Release