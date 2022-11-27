## ENTITY

### Status (enum??? а надо ли?)
* NewConfirmed
* TotalConfirmed
* NewDeaths
* TotalDeaths
* NewRecovered
* TotalRecovered

### WORLD (сделать просто фиксированным объектом среди COUNTRY)
### Country
* id
* name
* slug
* code
* _timestampable_

### Covid
* id
* сonfirmed: int
* deaths: int
* recovered: int
* active (вычисляемое): int
* apiTimestamp: dateTime (раз в сутки?)
* _timestampable_
Enum: Status
ManyToOne: Country

### Stat
* id
* newConfirmed
* totalConfirmed
* newDeaths
* totalDeaths
* newRecovered
* totalRecovered
* apiTimestamp: dateTime (раз в сутки?)
* _timestampable_
* OneToOne: Country

### TASK's (планировщик)
updateCountryList: 1 day


