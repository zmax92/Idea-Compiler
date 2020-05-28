## Description

Develop a solution for creating a single list of records sorted in three different ways from the three disparate
source files. \
Requirements for the resultant lists include:

1) Records should list data in the following order: \
LastName, FirstName, Gender, DateOfBirth, FavoriteColor

2) List should be output 3 times: \
  Output 1 - sorted by Gender (Females before Males) then LastName ascending. \
  Output 2 - sorted by Date, ascending. \
  Output 3 - sorted by last name, descending. \
  The expected output can be in: output.txt

3) Dates need to be displayed as ##/##/####

4) It is safe to assume that there are never any separators (commas, pipes, spaces, dashes) in any of the
data, besides the separators themselves.

5) Please use only the standard libraries only. No outside libraries (eg. SQL, etc) are allowed.

6) You MUST write the sort algorithm yourself. If you do not write the sorting algorithm you will fail.

INPUT FILE FORMATS: (INCLUDED)

pipe.txt: \
LastName | FirstName | MiddleInitial | Gender | FavoriteColor | DateOfBirth \
space.txt: \
LastName FirstName MiddleInitial Gender DateOfBirth FavoriteColor \
comma.txt: \
LastName, FirstName, Gender, FavoriteColor, DateOfBirth

## Deployment
Run in terminal
```bash
$ php script.php
```
