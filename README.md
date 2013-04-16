# OpenLectures-Portal

This is open source implementation of a video aggregator for the video storage systems Vilean and MediaSite which are often used by universities.
It is based on:

* [CakePHP](http://cakephp.org/)
* [Twitter Bootstrap](http://twitter.github.com/bootstrap/)
* jQuery + [jQuery UI](http://jqueryui.com/)
* [jQuery NestedSortable](https://github.com/mjsarfatti/nestedSortable)
* Support for MediaSite and Vilea as external data sources.

# Some screenshots:

![](https://raw.github.com/srad/open-electure-portal/master/resources/screenshots/1.png)
![](https://raw.github.com/srad/open-electure-portal/master/resources/screenshots/2.png)
![](https://raw.github.com/srad/open-electure-portal/master/resources/screenshots/3.png)
![](https://raw.github.com/srad/open-electure-portal/master/resources/screenshots/4.png)
![](https://raw.github.com/srad/open-electure-portal/master/resources/screenshots/5.png)
![](https://raw.github.com/srad/open-electure-portal/master/resources/screenshots/6.png)
![](https://raw.github.com/srad/open-electure-portal/master/resources/screenshots/7.png)
![](https://raw.github.com/srad/open-electure-portal/master/resources/screenshots/8.png)

# License

This project has dual license. It's open source but you need a license to host it and use it.

# Code Notes

* Notice that the video data are fetched from external system and that the video synchronization is made synchronously.
* That must be changed to an async style or as a background job.
