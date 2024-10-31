=== Multisite Author Bio ===
Contributors: CodeAdapted
Tags: author, author bio, author description, multisite, multisite author
Requires at least: 5.0
Tested up to: 6.6.2
Stable tag: 1.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Multisite Author Bio allows you to easily manage unique user biographical information across each site in a WordPress Multisite network.

== Description ==

Multisite Author Bio simplifies managing unique user biographical information across multiple sites in a WordPress Multisite network. This plugin allows administrators to update author bios from a single user edit page, streamlining the process of managing bio variations across different sites without having to switch between site dashboards.

= Features =

- **Centralized Bio Management**: View and edit author bio variations for all sites from one user profile page.
- **No Site Switching**: Edit the author bio for multiple sites from a single location, without needing to switch between dashboards.
- **Seamless Multisite Integration**: Works seamlessly within WordPress Multisite environments, allowing bio information to be site-specific.
- **Data Retention Control**: Decide whether plugin data should be retained or deleted upon uninstallation via the data retention setting.

== Installation ==

1. Download and unzip the plugin folder.
2. Upload the `multisite-author-bio` directory to the `/wp-content/plugins/` directory.
3. **Network Activate** the plugin from the Network Admin **Plugins** page.
4. Once activated, you can manage author bios on any user profile page.

== Usage ==

= Manage Author Bio Variations =
1. Navigate to any user’s **Edit Profile** page.
2. Scroll down to the **Multisite Author Bio** section near the bottom of the page.
3. The dropdown will display the list of sites in your network. Select a site to view or edit the author bio for that specific site.
4. Enter or update the author bio in the provided field.
5. Click **Update User** to save the changes.
6. The updated bio will now appear on the selected site.

= Data Retention Setting =
1. Go to **Settings > Multisite Author Bio** in the Network Admin dashboard.
2. Enable or disable the **Clear Data on Uninstall** option:
   - **Enabled**: All plugin-related data, including author bio variations, will be deleted when the plugin is uninstalled.
   - **Disabled**: Data will be retained after uninstallation.

== Screenshots ==

1. Go to the Network **Plugins** page.
2. Upload and install Multisite Author Bio.
3. Network activate the plugin.
4. Main network site author biographical information.
5. Author bio on main network site.
6. Scroll down the user's edit page until you see Multisite Author Bio.
7. Click on the dropdown to select the site name you wish to update/view the author bio for.
8. The textarea will appear with the author bio for the site selected. You can update this by clicking `Update User`.
9. Updated author bio on another network site.
10. If you want to remove the plugin and clear the database of all data associated with it go to `Settings > Multisite Author Bio`.
11. Check `Clear translation data on uninstall` and click `Save Changes`.

== Frequently Asked Questions ==

= How does this plugin work? =
Once the plugin is network-activated, navigate to any user’s **Edit Profile** page and scroll to the **Multisite Author Bio** section. Use the dropdown to select a site, and then enter or update the bio for that site.

= Can I uninstall the plugin without losing data? =
Yes. By default, data is preserved when the plugin is uninstalled. However, you can choose to enable the **Clear Data on Uninstall** option in the plugin settings to remove all plugin-related data when the plugin is deleted.

= Can this plugin work on a single-site WordPress installation? =
No, this plugin is specifically built for WordPress Multisite environments. It will not provide functionality on a single-site installation.

== Changelog ==

= 1.0.3 =
* Improved multisite bio handling and bug fixes.

= 1.0.2 =
* Added ES translations and fixed translation issues.

= 1.0.1 =
* Added support for non-multisite installations.

= 1.0.0 =
* Initial plugin release.

== License ==

This plugin is licensed under the GPLv2 or later. You can view the full license here: [GPLv2 License](http://www.gnu.org/licenses/gpl-2.0.html).

== Credits ==

* Developed by CodeAdapted.
