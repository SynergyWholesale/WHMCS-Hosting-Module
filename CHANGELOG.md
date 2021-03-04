# Change Log

Synergy Wholesale WHMCS Hosting Module

## Unreleased Version [Updated xx/xx/2020]
### Added
-

### Changed
-

### Fixed
-

### Removed

## 1.6.6 [Updated 4/03/2021]
### Added
- UPGRADING.md file for upgrade instructions between module versions
  - Pease ensure that this file is always read between version upgrades
  - In this current version we recommend that every access their "Module Settings" tab in their Synergy Wholesale Hosting products configuration.
### Fixed
- Syncing services will no longer attempt to update custom fields that don't exist

## 1.6.5 [Updated 26/02/2021]
### Fixed
- Make Custom Hosting products the default when no product name is found

## 1.6.4 [Updated 25/02/2021]
### Added
- Added support for Email Hosting products

## 1.6.3 [Updated 12/12/2020]
### Fixed
- Resolved an issue logging into services in the WHMCS client area when using certain themes or server configurations
- Resolved an issue enabling and disabling temporary urls
- Resolved an issue checking firewall status and removing firewall blocks
- Resolved an issue when logins fail it would result in an internal server error

## 1.6.2 [Updated 11/12/2020]
### Fixed
- Resolved issue logging into service in WHMCS admin area

## 1.6.1 [Updated 03/12/2020]
### Fixed
- Resolved issue with syncing hosting service resource usage stats

## 1.6.0 [Updated 16/11/2020]
### Added 
- Added support for an upcoming product

## 1.5.5 [Updated 14/10/2020]
### Added 
- Added usage statistics to synchronise function

## 1.5.4 [Updated 12/06/2019]
### Fixed 
- Removed invalid usage statistic

## 1.5.3 [Updated 06/06/2019]
### Fixed 
- Fixed issue with usage stats not appearing

## 1.5.2 [Updated 08/05/2019]
### Fixed 
- Fixed file structure

## 1.5.1 [Updated 02/05/2019]
### Fixed 
- Synchronise hosting service custom fields on service create

## 1.5 [Updated 06/02/2019]
### Added
- Added local custom field for server hostname

## 1.4 [Updated 20/10/2018]
### Added
- Added usage sync method

## 1.3 [Updated 29/08/2018]
### Added
- Added toggling of temporary URL
- Added firewall block checking
- Added login to cPanel button to admin area

### Changed
- Service name servers are now synced and can be used in email templates


## 1.3 [Updated 29/08/2018]
### Added
- Added toggling of temporary URL
- Added firewall block checking
- Added login to cPanel button to admin area

### Changed
- Service name servers are now synced and can be used in email templates

## 1.2 [Updated 03/05/2018]
### Added
- Added support for custom hosting packages

## 1.1 [Updated 12/01/2018]
### Added
- Full support for WHMCS 7.x
- Store cPanel account IP address locally for use as email template merge field

### Changed
- Improve data synchronization function

### Fixed
- Fixes for various bugs

## 1.0 [Updated 21/07/2014]
Initial Release