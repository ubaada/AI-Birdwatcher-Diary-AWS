# -*- mode: ruby -*-
# vi: set ft=ruby :
class Hash
  def slice(*keep_keys)
    h = {}
    keep_keys.each { |key| h[key] = fetch(key) if has_key?(key) }
    h
  end unless Hash.method_defined?(:slice)
  def except(*less_keys)
    slice(*keys - less_keys)
  end unless Hash.method_defined?(:except)
end

Vagrant.configure("2") do |config|
  config.vm.box = "dummy"

  # VM # 1: The Webserver
  config.vm.define "webserver" do |webserver|
    webserver.vm.provider :aws do |aws, override|
      # Set the VM name for AWS console.
      aws.tags = {'Name' => "Weberver"}
      aws.region = "us-east-1"
      override.nfs.functional = false
      override.vm.allowed_synced_folder_types = :rsync
      # keypair name for SSH
      aws.keypair_name = "cosc349-l"
      # Static private to communicate between VMs once deployed.
      aws.private_ip_address = "172.31.16.11"
      # Location of keypair file
      override.ssh.private_key_path = "~/.ssh/cosc349-l.pem"
      # type of EC2 instance in AWS
      aws.instance_type = "t2.micro"
      # Security group containing inbound/outbound rules
      aws.security_groups = ["sg-00f266d3bdade44a0"]
      aws.availability_zone = "us-east-1a"
      aws.subnet_id = "subnet-ceb3ce83"
      # AMI (like Vagrant Box): bionic, amd64, hvm, ebs
      aws.ami = "ami-013da1cc4ae87618c"
      override.ssh.username = "ubuntu"
    end
    webserver.vm.provision "shell", inline: <<-SHELL
      apt-get update

      # Install apache for frontend
      apt-get install -y apache2 php libapache2-mod-php php-curl php-mysql

      # Change VM's webserver's configuration to use shared /vagrant/www folder.
      cp /vagrant/birdwatcher-website.conf /etc/apache2/sites-available/

      # Create directory to store images outside of shared vagrant drive 
      mkdir /etc/birdimages

      # Set permissions for that outside directory
      sudo chgrp -R www-data /etc/birdimages
      sudo chmod -R g+w /etc/birdimages
      
      # install our website configuration and disable the default
      a2ensite birdwatcher-website
      a2dissite 000-default
      service apache2 reload

      # Download images associated with the sample sql data.
      wget -O /etc/birdimages/1.jpg https://upload.wikimedia.org/wikipedia/commons/2/28/Cassin%27s_Finch_%28male%29.jpg
      wget -O /etc/birdimages/2.jpg https://www.birdwatchersdigest.com/bwdsite/wp-content/uploads/2018/06/Limpkin1-600.jpg
      wget -O /etc/birdimages/3.jpg https://live.staticflickr.com/7812/32127525697_ce93af4f5f_b.jpg

      # Create table and add sample data
      php /vagrant/sql/aws-sql-setup.php
    SHELL
  end

  # VM # 2: The AIserver
  config.vm.define "aiserver" do |aiserver|
    aiserver.vm.provider :aws do |aws, override|
      # Set the VM name for AWS console.
      aws.tags = {'Name' => "AIserver"}
      aws.region = "us-east-1"
      override.nfs.functional = false
      override.vm.allowed_synced_folder_types = :rsync
      # keypair name for SSH
      aws.keypair_name = "cosc349-l"
      # Static private ip for Webserver to connect to
      aws.private_ip_address = "172.31.16.13"
      # Location of keypair file
      override.ssh.private_key_path = "~/.ssh/cosc349-l.pem"
      # type of EC2 instance in AWS
      aws.instance_type = "t2.micro"
      # Security group containing inbound/outbound rules
      aws.security_groups = ["sg-00f266d3bdade44a0"]
      aws.availability_zone = "us-east-1a"
      aws.subnet_id = "subnet-ceb3ce83"
      # AMI (like Vagrant Box): bionic, amd64, hvm, ebs
      aws.ami = "ami-013da1cc4ae87618c"
      override.ssh.username = "ubuntu"
    end
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
      nohup python3 /vagrant/classifier/run-model.py &> /dev/null &
    SHELL
  end
end