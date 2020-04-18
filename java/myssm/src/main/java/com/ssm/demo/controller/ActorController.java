package com.ssm.demo.controller;

import com.ssm.demo.pagemodel.ActorGrid;
import com.ssm.demo.po.Actor;
import com.ssm.demo.service.ActorService;
import io.swagger.annotations.Api;
import io.swagger.annotations.ApiOperation;
import org.apache.poi.util.IOUtils;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.*;

import javax.servlet.ServletOutputStream;
import javax.servlet.http.HttpServletResponse;
import java.io.InputStream;
import java.util.List;


@Api(tags = "演员接口")
@Controller
public class ActorController {
	@Autowired
	private ActorService actorservice;
	
	@ApiOperation("获取所有演员列表")
	@RequestMapping(value="/actors",method = RequestMethod.GET)
	@ResponseBody
	public ActorGrid getactorlist(@RequestParam(value="current") int current, @RequestParam(value="rowCount") int rowCount){
		int total=actorservice.getactornum();
		List<Actor> list=actorservice.getpageActors(current,rowCount);
		ActorGrid grid=new ActorGrid();
		grid.setCurrent(current);
		grid.setRowCount(rowCount);
		grid.setRows(list);
		grid.setTotal(total);
		return grid;
	}
	
	@ApiOperation("修改一个演员")
	@RequestMapping(value="/actors",method = RequestMethod.PUT)
	@ResponseBody
	public Actor updateactor(@RequestBody Actor a){
		Actor actor=actorservice.updateactor(a);
		return actor;
	}
	
	@ApiOperation("获取一个演员")
	@RequestMapping(value="/actors/{id}",method = RequestMethod.GET)
	@ResponseBody
	public Actor getactorbyid(@PathVariable("id") short id){
		Actor a=actorservice.getActorByid(id);
		return a;
	}
	
	@ApiOperation("添加一个演员")
	@RequestMapping(value="/actors",method = RequestMethod.POST)
	@ResponseBody
	public Actor add(@RequestBody Actor a){
		Actor actor=actorservice.addactor(a);
		return actor;
	}
	
	@ApiOperation("删除一个演员")
	@RequestMapping(value="/actors/{id}",method = RequestMethod.DELETE)
	@ResponseBody
	public String delete(@PathVariable("id") String id){
		actorservice.delete(Short.valueOf(id));
		return "success";
	}
	
	@ApiOperation("把演员导出为Excel")
	@RequestMapping(value="/exportactor",method = RequestMethod.GET)
	public void export(HttpServletResponse response) throws Exception{
		InputStream is=actorservice.getInputStream();
		response.setContentType("application/vnd.ms-excel");
		response.setHeader("contentDisposition", "attachment;filename=AllUsers.xls");
		ServletOutputStream output = response.getOutputStream();
		IOUtils.copy(is, output);
	}
	
	@RequestMapping(value="/showactor",method = RequestMethod.GET)
	String showactor(){
		return "showactor";
	}
}
