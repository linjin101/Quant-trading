package com.ssm.demo.service;

import com.ssm.demo.po.Actor;

import java.io.InputStream;
import java.util.List;


public interface ActorService {
    List<Actor> getpageActors(int pagenum, int pagesize);
    int getactornum();
    Actor getActorByid(short id);
    Actor updateactor(Actor a);
    Actor addactor(Actor a);
    void delete(short id);
    InputStream getInputStream() throws Exception;
}
