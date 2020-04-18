package com.ssm.demo.mapper;

import com.ssm.demo.po.Actor;
import org.springframework.context.annotation.ComponentScan;
import org.springframework.stereotype.Component;
import org.springframework.stereotype.Repository;

import java.util.List;

//mybatis的实体类和hibernate实体类的不同是mybatis的实体类不需要加载到spring的beanFactory中，
//而是通过操作数据库的mapper来持久化数据,相当于DAO层。

public interface ActorMapper {
	public List<Actor> getAllactors();
	public void updateActorbyid(Actor a);
	public Actor getactorbyid(short id);
	public void insertActor(Actor a);
	public void delete(short id);
}
