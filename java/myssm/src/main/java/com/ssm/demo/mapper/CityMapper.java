package com.ssm.demo.mapper;

import com.ssm.demo.po.City;
import com.ssm.demo.po.Country;
import org.springframework.stereotype.Repository;

import java.util.List;

public interface CityMapper {
	List<City> getCitys();
	List<City> getCountrycity(String countryname);//获取某国家城市列表
	Country getCitysbyCountry(String countryname);
}
