# -*- mode: ruby -*-
# vi: set ft=ruby :

# he "2" in Vagrant.configure
# configures the configuration version 
Vagrant.configure("2") do |config|

  config.vm.box = "ubuntu/bionic64"

  config.vm.define "dbserver" do |dbserver|
    # set name
    dbserver.vm.hostname = "dbserver"
    # static ip to be enable communication between VMs.
    dbserver.vm.network "private_network", ip: "192.168.2.12"
    # permissions for a shared drive on a network.
    dbserver.vm.synced_folder ".", "/vagrant", owner: "vagrant", group: "vagrant", mount_options: ["dmode=775,fmode=777"]
    
    # Provisioning for dbserver VM
    dbserver.vm.provision "shell", inline: <<-SHELL
      apt-get update

      # Set up pre-set answers to mysql-server install to avoid prompt blocking the shell
      echo "mysql-server mysql-server/root_password password $MYSQL_PWD" | debconf-set-selections 
      echo "mysql-server mysql-server/root_password_again password $MYSQL_PWD" | debconf-set-selections

      # Install the MySQL database server.
      apt-get -y install mysql-server

      # Run some setup commands to get the database ready to use.
      # First create a database.
      echo "CREATE DATABASE fvision;" | mysql

      # Then create a database user "webuser" with the given password.
      echo "CREATE USER 'webuser'@'%' IDENTIFIED BY 'insecure_db_pw';" | mysql

      # Grant all permissions to the database user "webuser" regarding
      # the "fvision" database that we just created, above.
      echo "GRANT ALL PRIVILEGES ON fvision.* TO 'webuser'@'%'" | mysql
      
      # Set the MYSQL_PWD shell variable that the mysql command will
      # try to use as the database password ...
      export MYSQL_PWD='insecure_db_pw'

      # Set up table and sample data.
      cat /vagrant/sql/setup-database.sql | mysql -u webuser fvision

      # Enable public access to db server. So our webserver can contact dbserver.
      sed -i'' -e '/bind-address/s/127.0.0.1/0.0.0.0/' /etc/mysql/mysql.conf.d/mysqld.cnf

      # Restart mysql for changes to take effect.
      service mysql restart
    SHELL
  end

  config.vm.define "aiserver" do |aiserver|
    # set name
    aiserver.vm.hostname = "aiserver"
    # static ip to be enable communication between VMs.
    aiserver.vm.network "private_network", ip: "192.168.2.13"
    # permissions for a shared drive on a network.
    aiserver.vm.synced_folder ".", "/vagrant", owner: "vagrant", group: "vagrant", mount_options: ["dmode=775,fmode=777"]
    
    # Provisioning for aiserver VM
    aiserver.vm.provision "shell", inline: <<-SHELL
      apt-get update

      # Install pip to install python packages
      apt-get install -y python3-pip

      #Install python packahes
      pip3 install pillow
      pip3 install numpy
      pip3 install "https://dl.google.com/coral/python/tflite_runtime-2.1.0.post1-cp36-cp36m-linux_x86_64.whl"
      pip3 install flask

      # Run classifier model server. 
      # Nohup so it doesn't after vagrant stops the provisioning shell
      # Redirect output to stop blocking the shell
      nohup python3 /vagrant/classifier/run-model.py &> /home/vagrant/fl.txt &
    SHELL
  end

  # Placing webserver at the end to wait for otherservices to boot
  config.vm.define "webserver" do |webserver|
    # set name
    webserver.vm.hostname = "webserver"
    # for only the webserver VM port for host access
    webserver.vm.network "forwarded_port", guest: 80, host: 8080, host_ip: "127.0.0.1"
    # static ip to be enable communication between VMs.
    webserver.vm.network "private_network", ip: "192.168.2.11"
    # permissions for a shared drive on a network.
    webserver.vm.synced_folder ".", "/vagrant", owner: "vagrant", group: "vagrant", mount_options: ["dmode=775,fmode=777"]
    
    # Provisioning for webserver VM
    webserver.vm.provision "shell", inline: <<-SHELL
      apt-get update

      # Install apache for frontend
      apt-get install -y apache2 php libapache2-mod-php php-curl php-mysql

      # Change VM's webserver's configuration to use shared /vagrant/www folder.
      cp /vagrant/birdwatcher-website.conf /etc/apache2/sites-available/
      
      # install our website configuration and disable the default
      a2ensite birdwatcher-website
      a2dissite 000-default
      service apache2 reload

      # Download images associated with the sample sql data.
      mkdir /vagrant/www/birdimages
      wget -O /vagrant/www/birdimages/1.jpg https://upload.wikimedia.org/wikipedia/commons/2/28/Cassin%27s_Finch_%28male%29.jpg
      wget -O /vagrant/www/birdimages/2.jpg https://www.birdwatchersdigest.com/bwdsite/wp-content/uploads/2018/06/Limpkin1-600.jpg
      wget -O /vagrant/www/birdimages/3.jpg https://live.staticflickr.com/7812/32127525697_ce93af4f5f_b.jpg

    SHELL
  end
end
