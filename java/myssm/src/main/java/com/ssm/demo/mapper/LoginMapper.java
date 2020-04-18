package com.ssm.demo.mapper;

import com.ssm.demo.po.Staff;
import org.springframework.stereotype.Repository;

public interface LoginMapper {
	Staff getpwdbyname(String name);
}
