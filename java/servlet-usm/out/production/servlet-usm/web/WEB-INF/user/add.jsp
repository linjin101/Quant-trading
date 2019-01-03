<%--
  Created by IntelliJ IDEA.
  User: Administrator
  Date: 2019/01/02 0002
  Time: 17:51
  To change this template use File | Settings | File Templates.
--%>
<%@ page contentType="text/html;charset=UTF-8" language="java" %>
<html>
<head>
    <title>Title</title>
</head>
<body>
<fieldset><legend>新增用户</legend>
    <form action="addUser" method="post">
        <p>姓名：<input type="text" name="userName"></p>
        <p>性别：<input type="radio" name="userGender" vaule="man">男<input type="radio" name="man">女<input type="radio" name="woman"></p>
        <p>电话：<input type="text" name="userPhone"></p>
        <p><input type="submit" value="提交"></p>
    </form>
</fieldset>
</body>
</html>
