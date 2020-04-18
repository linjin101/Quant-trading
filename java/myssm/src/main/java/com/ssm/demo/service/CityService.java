package com.ssm.demo.service;

import com.ssm.demo.po.City;
import com.ssm.demo.po.Country;

import java.util.List;


public interface CityService {
	List<City> getCitylist();
	List<City> getpagecitylist(int pagenum, int pagesize);
	List<City> getCountryCity(String Countryname);
	List<City> getpageCountryCity(String Countryname, int pagenum, int pagesize);
	Country getCountryCitys(String Countryname);//一对多
}
