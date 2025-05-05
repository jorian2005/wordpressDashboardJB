# Dashboard JB

Dashboard JB is a WordPress plugin designed to customize and enhance the WordPress dashboard and login experience. It provides various features to modify the login page, manage maintenance mode, configure SEO settings, handle redirects, view analytics, and more.

## Features

- **Custom Login Page**: 
  - Change the login page slug.
  - Customize the login page colors, background image, and logo.
  - Add custom footer text to the login page.

- **Maintenance Mode**:
  - Enable or disable maintenance mode.
  - Customize the maintenance mode page with a title, message, logo, and background image.

- **SEO Settings**:
  - Generate an XML sitemap for your site.
  - Configure SEO-related settings for your WordPress site.

- **Redirect Settings**:
  - Manage custom redirects for your site.
  - Add or edit URL redirects directly from the admin interface.

- **Analytics**:
  - View site analytics and visitor statistics.
  - Export analytics data as CSV or JSON.

- **Dashboard Widgets**:
  - Add custom widgets to the WordPress dashboard.

## Installation

1. Download the plugin files and upload them to the `wp-content/plugins/DashboardJB` directory.
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
2. Enable maintenance mode and customize the maintenance page with:
   - Title
   - Message
   - Logo
   - Background image

### SEO Settings
1. Navigate to **Dashboard JB > SEO Instellingen**.
2. Generate an XML sitemap for your site.
3. Configure other SEO-related options.

### Redirect Settings
1. Navigate to **Dashboard JB > Redirect Instellingen**.
2. Add or manage custom redirects.

### Analytics
1. Navigate to **Dashboard JB > Analytics**.
2. View site analytics and visitor statistics.
3. Export analytics data as CSV or JSON.

### Dashboard Widgets
1. Navigate to the WordPress dashboard to view custom widgets added by the plugin.

## Development

### File Structure
- `dashboard-jb.php`: Main plugin file that initializes the plugin and registers admin menus.
- `admin/dashboard-widget.php`: Adds custom dashboard widgets.
- `admin/settings-page.php`: Handles the settings page for the plugin.
- `admin/dashboard.php`: Manages the dashboard customization.
- `admin/analytics.php`: Handles analytics tracking and export functionality.
- `includes/editLogin.php`: Handles custom login page settings.
- `includes/loginCustomizer.php`: Applies custom styles and functionality to the login page.
- `includes/maintenance.php`: Manages maintenance mode functionality.
- `includes/seo.php`: Handles SEO settings and sitemap generation.
- `includes/redirect.php`: Manages redirect settings.
- `includes/enqueue.php`: Enqueues styles and scripts for the plugin.
- `assets/css/style.css`: Styles for the plugin's admin pages and customizations.
- `assets/css/login.css`: Styles for the custom login page.
- `assets/js/clu-admin.js`: JavaScript for handling media uploads in the admin interface.
- `assets/images/logo.svg`: Default logo for the plugin.

### Hooks and Filters
- `admin_menu`: Adds the plugin's menu and submenus.
- `admin_enqueue_scripts`: Enqueues scripts and styles for the admin interface.
- `login_head`: Adds custom styles to the login page.
- `login_footer`: Adds custom footer text to the login page.
- `init`: Handles custom login and logout functionality.
- `template_redirect`: Enables maintenance mode when active.
- `wp_dashboard_setup`: Adds custom dashboard widgets.

## License

This plugin is licensed under the [GPL 3.0](https://www.gnu.org/licenses/gpl-3.0.html).

## Author

Developed by [Jorian Beukens](https://jorianbeukens.nl).