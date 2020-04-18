package com.ssm.demo.service.impl;


import com.ssm.demo.mapper.LoginMapper;
import com.ssm.demo.po.Staff;
import com.ssm.demo.service.LoginService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Isolation;
import org.springframework.transaction.annotation.Propagation;
import org.springframework.transaction.annotation.Transactional;

@Transactional(propagation= Propagation.REQUIRED,isolation= Isolation.DEFAULT,timeout=5)
@Service
public class LoginServiceImpl implements LoginService{
	@Autowired
	public LoginMapper loginmapper;
	public String getpwdbyname(String name) {
		Staff s=loginmapper.getpwdbyname(name);
		if(s!=null)
		return s.getPassword();
		else
		return null;
	}

}
