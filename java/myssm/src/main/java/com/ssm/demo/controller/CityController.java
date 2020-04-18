package com.ssm.demo.controller;

import com.ssm.demo.pagemodel.CityGrid;
import com.ssm.demo.po.City;
import com.ssm.demo.po.Country;
import com.ssm.demo.service.CityService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.ResponseBody;

import java.util.List;


@Controller
public class CityController {
	@Autowired
	CityService cityservice;
	@RequestMapping("/getcitys")
	@ResponseBody
	CityGrid getcitys(@RequestParam("current") int current, @RequestParam("rowCount") int rowCount){
		int total=cityservice.getCitylist().size();
		List<City> list=cityservice.getpagecitylist(current,rowCount);
		CityGrid grid=new CityGrid();
		grid.setCurrent(current);
		grid.setRowCount(rowCount);
		grid.setRows(list);
		grid.setTotal(total);
		return grid;
	}
	
	@RequestMapping("/city")
	String city(){
		return "city";
	}
	
	@RequestMapping("/country")
	String country(){
		return "country";
	}
	
	@RequestMapping("/getchainacity")
	@ResponseBody
	CityGrid getchinacity(@RequestParam("current") int current, @RequestParam("rowCount") int rowCount){
		List<City> citys=cityservice.getCountryCity("China");
		int total=citys.size();
		List<City> clist=cityservice.getpageCountryCity("China", current, rowCount);
		CityGrid grid=new CityGrid();
		grid.setCurrent(current);
		grid.setRowCount(rowCount);
		grid.setRows(clist);
		grid.setTotal(total);
		return grid;
	}
	
	
	@RequestMapping("/countrycity")
	@ResponseBody
	Country getcountrycity(@RequestParam("country")String country){
		Country a=cityservice.getCountryCitys(country);
		return a;
	}
	
}
