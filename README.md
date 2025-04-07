# Dashboard JB

Dashboard JB is a WordPress plugin designed to customize and enhance the WordPress dashboard and login experience. It provides various features to modify the login page, manage maintenance mode, configure SEO settings, and handle redirects.

## Features

- **Custom Login Page**: 
  - Change the login page slug.
  - Customize the login page colors, background image, and logo.
  - Add custom footer text to the login page.

- **Maintenance Mode**:
  - Enable or disable maintenance mode.
  - Customize the maintenance mode page.

- **SEO Settings**:
  - Configure SEO-related settings for your WordPress site.

- **Redirect Settings**:
  - Manage custom redirects for your site.

## Installation

1. Download the plugin files and upload them to the `wp-content/plugins/wordpressDashboardJB` directory.
2. Activate the plugin through the WordPress admin dashboard under the "Plugins" menu.

## Usage

### Custom Login Settings
1. Navigate to **Dashboard JB > Custom Login** in the WordPress admin menu.
2. Configure the login page settings, including:
   - Login slug
   - Footer text
   - Theme colors
   - Background and logo images

### Maintenance Mode
1. Navigate to **Dashboard JB > Onderhoudsmodus**.
2. Enable maintenance mode and customize the maintenance page.

### SEO Settings
1. Navigate to **Dashboard JB > SEO Instellingen**.
2. Configure SEO-related options for your site.

### Redirect Settings
1. Navigate to **Dashboard JB > Redirect Instellingen**.
2. Add or manage custom redirects.

## Development

### File Structure
- `mainpage.php`: Registers the admin menu and submenu pages.
- `editLogin.php`: Handles custom login page settings.
- `loginCustomizer.php`: Applies custom styles and functionality to the login page.
- `maintenance.php`: Manages maintenance mode functionality.
- `seo.php`: Handles SEO settings.
- `redirect.php`: Manages redirect settings.
- `clu-admin.js`: JavaScript for handling media uploads in the admin interface.
- `main.css`: Styles for the plugin's admin pages.

### Hooks and Filters
- `admin_menu`: Adds the plugin's menu and submenus.
- `admin_enqueue_scripts`: Enqueues scripts and styles for the admin interface.
- `login_head`: Adds custom styles to the login page.
- `login_footer`: Adds custom footer text to the login page.
- `init`: Handles custom login and logout functionality.

## License

This plugin is licensed under the [GPL 3.0](https://www.gnu.org/licenses/gpl-3.0.html).

## Author

Developed by [Jorian Beukens](https://jorianbeukens.nl).