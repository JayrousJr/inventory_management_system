@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/filament-v3
SET COMPOSER_RUNTIME_BIN_DIR=%~dp0
php "%BIN_TARGET%" %*
