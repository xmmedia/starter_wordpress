#!/bin/sh

echo
echo '-- Setting up PRODUCTION site --'
echo

echo "Paths:"
CURRENT_PATH=$PWD
if [[ $CURRENT_PATH = /chroot* ]]; then
    BASE="${CURRENT_PATH:7}";
else
    BASE=${CURRENT_PATH}
fi
RELEASES="$BASE/releases";
RELEASE="$RELEASES/1";
SHARED="$BASE/shared"
printf "Current:  ${PWD}\nRoot:     ${BASE}\nReleases: ${RELEASES}\nRelease:  ${RELEASE}\nShared:   ${SHARED}\n\n"

if [ -d "$RELEASES" ]; then
    echo "The releases dir (${RELEASES}) already exists. Cannot continue."
    exit 1
fi

PHP_VERSION=$(php -v|grep --only-matching --perl-regexp "(PHP )\d+\.\\d+\.\\d+"|cut -c 5-7)
PHP_MINIMUM_VERSION=8.0
printf "Current PHP version: ${PHP_VERSION}\nMinimum PHP version: ${PHP_MINIMUM_VERSION}\n"
if [ $(echo "$PHP_VERSION >= $PHP_MINIMUM_VERSION" | bc) -eq 0 ]; then
    echo "************"
    echo "Will switch PHP version to ${PHP_MINIMUM_VERSION}"
    echo "************"
fi
echo

read -p "Are you in the domain dir, where the html dir is and the paths above are correct? (Y/n) " -n 1 -r
echo    # move to a new line after response
if [[ ! $REPLY =~ ^[Y]$ ]]; then
    echo
    echo 'Cancelled'
    echo
    [[ "$0" = "$BASH_SOURCE" ]] && exit 1 || return 1 # handle exits from shell or function but don't exit interactive shell
fi
printf "\n\n"

cd $BASE || exit
echo "Working in: $PWD"
printf "\n\n"

echo "Creating ssh key dir/files"
mkdir ~/.ssh
touch ~/.ssh/authorized_keys
chmod 700 ~/.ssh
chmod 600 ~/.ssh/authorized_keys

echo "Install oh-my-zsh"
sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)" "" --unattended
printf "\nDISABLE_AUTO_TITLE=\"true\"" >> ~/.zshrc
printf "\n\n"

if [ $(echo "$PHP_VERSION >= $PHP_MINIMUM_VERSION" | bc) -eq 0 ]; then
    echo "Switching PHP version to ${PHP_MINIMUM_VERSION}"
    printf "\nsource /opt/remi/php80/enable" >> ~/.zshrc
    printf "\nsource /opt/remi/php80/enable" >> ~/.bashrc
    source /opt/remi/php80/enable
    php -v
fi

echo "Creating dirs in ${BASE}"
cd $BASE || exit
mkdir -p $RELEASE/public/app/uploads
mkdir -p $RELEASE/public/app/wflogs
mkdir -p $RELEASE/log
mkdir -p $SHARED/public/app/uploads
mkdir -p $SHARED/public/app/wflogs
mkdir -p $SHARED/log
ln -s $RELEASE current
rm -rf html
ln -s current/public html
ln -s $SHARED/public/app/uploads $RELEASE/public/app/uploads
ln -s $SHARED/public/app/wflogs $RELEASE/public/app/wflogs
ln -s $SHARED/log $RELEASE/log
printf "\n\n"

cd $RELEASE || exit

echo "Ready! You can attempt a deploy."
echo

echo "To switch to oh-my-zsh, run: /bin/zsh"
echo
