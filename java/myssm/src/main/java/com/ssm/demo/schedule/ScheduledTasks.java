package com.ssm.demo.schedule;

import com.ssm.demo.po.Actor;
import com.ssm.demo.service.ActorService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.scheduling.annotation.Scheduled;
import org.springframework.stereotype.Component;

import java.util.List;

@Component
public class ScheduledTasks {
	
	@Autowired
	private ActorService actorservice;
	
	    @Scheduled(cron="0 0/1 * * * ?")
	    public void reportCurrentTime() {
	        List<Actor> list=actorservice.getpageActors(1, 10);
	        System.out.println(list.get(0).getFirst_name());
	    }
}
