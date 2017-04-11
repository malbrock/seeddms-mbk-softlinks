# seeddms-mbk-softlinks
SeedDMS Softlinks Extension

Extension for SeddDMS >5.0 that enables links from a Folder View to folders or documents located on other folders.

It looks for an attribute named "isLink". If isLink is TRUE, then looks for another attribute called "linkedId". If linkedId is >0, then the default link on the view is replaced by a link to the file or folder with ID equal to the value of the attribute linkedId. A folder links to another folder, and a document links to another document.

Installation
============

Copy the folder "mbk-softlinks", and all of it contents, to the path <SeedDMS_root>/ext/

Go to the Administration section of SeedDMS and then to "Manage Extensions". Verify that mbk-softlinks is listed.

Go to the Administration section of SeedDMS and then to "Attribute Definition".

Create attribute "isLink" with the following parameters:
 - Object Type: All 
 - Type: Logic (boolean)
 - Allow multiple: Unchecked

Create attribute "linkedId" with the following parameters:
 - Object Type: All 
 - Type: Integer
 - Allow multiple: Unchecked
 
 Usage
 =====
 
 To convert a document/folder to a Softlink, edit the document/folder attributes.
 
 Set the isLink field to checked.
 Set the value of linkedId to the ID of the target document/folder you wish to link to.
 
