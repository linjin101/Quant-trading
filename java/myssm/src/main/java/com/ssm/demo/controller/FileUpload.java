package com.ssm.demo.controller;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.core.env.Environment;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.ResponseBody;
import org.springframework.web.multipart.MultipartFile;

import javax.servlet.http.HttpServletRequest;
import java.io.File;

@Controller
public class FileUpload {
	
@Value("${uploadfiles.path}") private String address;

@Autowired
Environment ev ;

	
@RequestMapping(value="/uploadfile",method = RequestMethod.POST)
@ResponseBody
public String fileupload(@RequestParam MultipartFile uploadfile, HttpServletRequest request){
	try{
		String filename=uploadfile.getOriginalFilename();
//		String targetDir=request.getSession().getServletContext().getRealPath("uploadfiles");
		File targetfile=new File(this.address,filename);//"D:\\"
		uploadfile.transferTo(targetfile);
	}catch(Exception e){
		e.printStackTrace();
	}
	return "success";
}

@RequestMapping(value="/uploadfile2",method = RequestMethod.POST)
@ResponseBody
public String fileuploads(@RequestParam MultipartFile[] uploadfile, HttpServletRequest request){
	try{
		if(uploadfile!=null&&uploadfile.length>0){  
            //循环获取file数组中得文件  
            for(int i = 0;i<uploadfile.length;i++){  
                MultipartFile file = uploadfile[i];
                if(file.getSize()==0){
                	continue;
                }
                //保存文件  
                String filename=file.getOriginalFilename();
                this.address = ev.getProperty("uploadfiles.path");
//        		String targetDir=request.getSession().getServletContext().getRealPath("uploadfiles");
        		File targetfile=new File(this.address,filename);//"D:\\"
        		file.transferTo(targetfile);
            }  
        }  
		
	}catch(Exception e){
		e.printStackTrace();
	}
	return "success";
}

@RequestMapping("/fileupload")
String fileupload(){
	return "fileupload";
}

}
