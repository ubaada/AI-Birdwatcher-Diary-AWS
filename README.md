

# AI-Birdwatcher-Diary
Easier way of keeping record of your bird sightings by automatically recognizing the species from their images and storing them in a database.

## Preview

Video demonstration of what deploying the project would look like on AWS.

[![Demonstration](https://img.youtube.com/vi/VH4VKRG42l0/0.jpg)](https://www.youtube.com/watch?v=VH4VKRG42l0)


## Requirements 
You need: 
- Vagrant
- vagrant-aws plugin
- Git (optional)

## Design
(Read aws-report.pdf for detailed info)

![image](https://user-images.githubusercontent.com/22624761/196929947-b58de206-b63d-4f29-ae02-7d25e156e5df.png)


## Deploying

Cd into folder you want this project to be and run
```
git clone https://github.com/ubaada/AI-Birdwatcher-Diary.git
```
(or you can download the .zip file directly from GitHub.)

1. Set up a Security Group in AWS console:
Inbound Rules:
<table>
  <tr>
    <th>Type</th>	<th>Protocol</th>	<th>Port range</th>	<th>Source</th>	<th>Description - optional</th>
  </tr>
   
   <tr>
    <td>HTTP</td>	<td>TCP</td>	<td>80</td>	<td>0.0.0.0/0</td>	<td>Public web access</td>
  </tr>
  <tr>
    <td>HTTP</td>	<td>TCP</td>	<td>80</td>	<td>::/0</td>	<td>Allow all outbound traffic</td>
  </tr>
  <tr>
    <td>All TCP</td>	<td>TCP</td>	<td>0 - 65535</td>	<td>[id-of-same-security group]</td>	<td>Inter-VM (ec2-db) Communication</td>
  </tr>
  <tr>
    <td>SSH</td>	<td>TCP</td>	<td>22</td>	<td>0.0.0.0/0</td>	<td>SSH for admin</td>
  </tr>

</table>

Outbound rules:
<table>
  <tr>
    <th>Type</th>	<th>Protocol</th>	<th>Port range</th>	<th>Source</th>	<th>Description - optional</th>
  </tr>
  <tr>
    <td>All traffic</td>	<td>All</td>	<td>All</td>	<td>0.0.0.0/0</td>	<td>Allow all outbound traffic</td>
  </tr>
<table>
  
2. Set up SSH:
For SSH to work create a key-pair named “cosc349-l.pem” in AWS console. AWS does not allow SSH login via passwords. 
Place the key file in ```~/.ssh```
3. Create an AWS RDS MySQL database using AWS console. 
4. Clone the git repo and CD into it. Edit the VMs in Vagrantfile to have your AWS Security Group.
5. Set up database details in ```.config/db-config.php``` file in the cloned repo:

```
 <?php
 $db_host   = '[end-point-here]:[port-number-usually-3306]';
 $db_name   = '';
 $db_user   = '';
 $db_passwd = '';
 ?>
```

6. Install vagrant-aws plugin:  ``` $ vagrant plugin install vagrant-aws ```
7. Set up the AWS environment variables  for Vagrant to be able to talk with AWS.
8. Start Vagrant deployment: ```$ vagrant up --provider=aws```

