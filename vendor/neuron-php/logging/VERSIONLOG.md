## 0.5.2

## 0.5.1

## 0.7.37

## 0.7.36 2020-04-09
* Renamed SessionSingleton to Session.
* Added phone number formatter.

## 0.7.35 2020-03-27
* Added date compare.
* Added UnitConversion.

## 0.7.34 2020-02-25
* Fixed an issue with filterscalar array using the wrong datatype.

## 0.7.33 2019-06-28
* Added SystemTimer singleton.
* Updated timer start to call resit.
* Added ITimer interface.
* Added date today, tomorrow, yesterday, isWeekend, getDay, getWorkingDays.

## 0.7.32
* Updated the timer object to support lap type functionality.

## 0.7.31 2019-05-22
* Added FirstMI parser.

## 0.7.30 2019-05-21
* Constants defined for international and US phone number regex patterns.

## 0.7.29 2019-03-26
* Boolean validator refactor to pass values "on, off, yes, no" as boolean.

## 0.7.28 2019-03-26
* Numeric validation class created.

## 0.7.27 2019-02-16
* Timer now returns elapsed while not stopped.
* Added Timer::reset()

## 0.7.26 2019-01-10
* Updated float validation to handle strings and floats.

## 0.7.25 2018-01-06
* Added date normalization to the date range constructor. No longer
  need the fail cases for the validator because the dates can't fail.

## 0.7.24 2018-12-29
* Added getAsString to Version.

## 0.7.23 2018-12-27
* Added tests for WebHook.
* Added WebHookResponse
* Added ext-json and ext-curl to composer requirements.
* Added normalization the date formatter so it seeks to find the most
  likely date format and reassemble to a standard format.

## 0.7.22 2018-11-01
* Updated Version data object to add the build number now supported
by version.json/bump.

## 0.7.21 2018-10-25
* Added WebHook and IWebHook.
* Implemented WebHookPost log destination.
* Added StringLength validator.
* Fixed up float validator to accommodate floats in strings.

## 0.7.20 2019-09-24
* Improved boolean loose mode validation to include '0', '1' and
    'true', 'false.

## 0.7.19 2019-09-24
* Added 'loose' matching to boolean validation that checks for int 0 or 1.

## 0.7.18 2018-09-15
* Added Boolean validator.
* Added toCamelCase and toSnakeCase methods to StringData.

## 0.7.17 2018-09-01
* Added DateTime validator. Updated date validation logic.

## 0.7.16 2018-09-01
* Added ArrayData validator.

## 0.7.15 2018-08-26
* Added htmlemail formatter.
* Enhanced the formatters.

## 0.7.14 2018-08-16
* Modified the Log Singleton to use a LogMux instead of a simple Logger.
* Finished email log destination.
* First shot at slack log destination.
* Slight refactoring for logging.

## 0.7.13 2018-07-28
* Added EIN validator.
* Added International validation for PhoneNumber.
* Added getLengthInDays to DateRange.

## 0.7.9
* Added Name validator.
* Added PhoneNumber validator.
* Added NumericRange datatype.
* Added NumericRange validator.
* Added NumberWithinRange validator.

## 0.7.8
* Added the validatable interface.
* Slight refactoring to support validators using the visitor pattern.
* Added ICollection to validation.

## 0.7.7
* Added session filter.
* Added filterArray to all filter classes.
* Added Currency validator.
* Added Positive validator.
* Added Validator Collection.
* Added StringData quote.

## 0.7.6 2018-06-05
* Regressed to make compatible with php 5.6

## 0.7.5 2018-05-02
* Version->loadFromFile now defaults to version.json for the filename.
* Updated commandlinebase to handle parameters in php 7.x

## 0.7.4 2018-04-21
* Added Version data object.

## 0.7.3 2018-02-17
* Added Cookie filter.

## 0.7.2 2018-01-15
* Adds Date::only()
* Julian date now strips time before processing.

## 0.7.1 2017-11-19
* Fixes an issue with setting log level in singleton.
* Refactors differenceUnitAsText
* Adds daysAsText

## 0.7.0 2017-08-14
* Adds Log singleton wrapper.
* Fixes an issue with inheriting from the singleton memory class.
* PHP7.1 updates to ILogger.

## 0.6.12
* Added DateRange object.
* Refactored DateRange validator to DateWithinRange validator.
* Added DateRange validator.

## 0.6.11

* Fixups for php7
* Adds PolicyTrait

## 0.6.10 2017-02-22

* Adds Time Validation.
* Added StringData class.

## 0.6.9 2017-01-09

* Adds filterArray method in Post.

## 0.6.8 2016-10-21

* Adds GpsPoint constructor.
* Adds File\Temporary.
* Adds Formatter.

## 0.6.7

* Adds GpsPoint data object.

## 0.6.6 2016-08-29

* Adds Util/Timer.
* Adds differenceAsText.

## 0.6.4 2016-08-16

* Adds server filter.

## 0.6.3

* Adds Language text mapper.
* Adds memcache singleton.
* More tests.
* Adds Memory settings - mostly only useful for tests.

## 0.6.1

* Fix to observer variadic parameters.

## 0.6.0

* Fixes phpcs/phpmd warnings.
* Adds IRunnable interface.
* Adds Observer pattern.
* Refactors namespaces relating to patterns.

## 0.5.29

* Includes tests for Criteria pattern And, Or and Not.
* Adds KeyValue Criterion.

## 0.5.28

* Updates travis config.

## 0.5.27

* Adds Criteria pattern.
* Adds ArrayHelper.
* Adds DateRange, NotNull and NotEmpty validators.

## 0.5.26

* Data filters for get and post.

## 0.5.25

* Adds column vs data sanity check in CSV parser.

## 0.5.24 2015-12-27
* Fix to IApplication registry methods.

## 0.5.23 2015-12-27

* Adds Singleton mechanisms
* Adds Registry
* Application->run now returns a boolean result

## 0.5.22 2015-12-20

* Adds setRunLevel to ILogger.
* Remaps settings control in ApplicationBase.

## 0.5.21 2015-12-20

* Removes LogMux level mapping. Upon further thought, this should be handled by each individual log's runlevel.

## 0.5.20 2015-12-14
* :|

## 0.5.19 2015-12-14
* Ugh.

## 0.5.18i 2015-12-14
* Fix to applicationbase.

## 0.5.17 2015-12-14

* ApplicationBase now extends LoggableBase.
* Fixes to LoggableBase.

## 0.5.16 2015-12-11

* Adds reset method to LogMux.

## 0.5.15 2015-12-11

* Adds LogMux and the ability to map logs to different levels.
* Adds LogMux unit test.
* Adds a parameter to the plain text log constructor to disable the timestamp.

## 0.5.14 2015-11-09

* Adds LastFirstMI parser.

## 0.5.13 2015-10-25

* Adds initial H\Error class.

## 0.5.12 2015-10-23
* Fix (again) for subtract days.

## 0.5.11 2015-10-23
* Added IApplication
* Added application settings.

## 0.5.10 2015-10-04
* Fixed random text in ISettingSource.php

## 0.5.9 2015-09-24
* Fixed missing $ in subtractDays.

## 0.5.8 2015-09-22
* Added Date::subtractDays

## 0.5.7
* Removed phpdoc cache files.

## 0.5.6 2015-09-09
* Cleanup of --help information for commandline applications.

## 0.5.5 2015-09-08
* More unit tests.
* Beginning of examples.
* Updates to CommandlineApplication.

## 0.5.4 2015-09-03
