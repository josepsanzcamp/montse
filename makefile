
SHELL := /bin/bash

all:
	soffice --headless --convert-to html Glossari_Signes.xlsx
	time -p php make.php
