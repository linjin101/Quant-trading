package com.ssm.demo.controller;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;


@Controller
public class DruidController {
 	
	@RequestMapping("/druid")
	String druid(){
		return "druid";
	}
  }
