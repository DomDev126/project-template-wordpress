#
# Install WordPress
#
./vendor/bin/wp --allow-root \
	core install \
		--url=http://localhost:8000  \
		--title=WordPress \
		--admin_user=admin \
		--admin_password=admin \
		--admin_email=root@localhost.localdomain 

# 
# Remove default content
# 
./vendor/bin/wp --allow-root comment delete 1 --force
./vendor/bin/wp --allow-root post delete 1 --force
./vendor/bin/wp --allow-root post delete 2 --force

# 
# Setup friendly URLs
# 
./vendor/bin/wp --allow-root rewrite structure "/%year%/%monthnum%/%day%/%postname%/"

#
# Install plugins
#

# WordPress Directory plugins
./vendor/bin/wp --allow-root plugin install --force --activate disable-wordpress-updates --version=1.4.2
./vendor/bin/wp --allow-root plugin install --force --activate wp-theme-plugin-editor-disable --version=1.0.0
./vendor/bin/wp --allow-root plugin install --force --activate disable-comments --version=1.2
./vendor/bin/wp --allow-root plugin install --force --activate jetpack --version=3.2
# Custom plugins
./vendor/bin/wp --allow-root plugin install --force --activate https://github.com/QoboLtd/Qobo-WP-Custom-Theme-Path/archive/v1.0.0.zip

# 
# Active plugins
# 
for WP_INACTIVE_PLUGIN in $(./vendor/bin/wp --allow-root plugin list --status=inactive --field=name)
do
	./vendor/bin/wp --allow-root plugin activate $WP_INACTIVE_PLUGIN
done

#
# Install themes
# 

# WordPress theme directory (default WP theme, just in case something breaks)
./vendor/bin/wp --allow-root theme install --force twentyfourteen
# Custom parent theme (slug or, local or remote ZIP)
./vendor/bin/wp --allow-root theme install --force https://github.com/QoboLtd/Qobo-WP-Generic-Theme/archive/v1.0.0.zip
# Custom child theme (this is just a good practice)
./vendor/bin/wp --allow-root theme activate custom

#
# Setting options
# 

# WordPress is in the wp/ folder, but home should be in the root
./vendor/bin/wp --allow-root option update home 'http://localhost:8000'
# Disable comments everywhere, for the Disable Comments plugin
./vendor/bin/wp --allow-root option update disable_comments_options '{"disabled_post_types":["post","page","attachment"],"remove_everywhere":true,"permanent":false,"extra_post_types":false,"db_version":6}' --format=json

# vi:ft=sh
