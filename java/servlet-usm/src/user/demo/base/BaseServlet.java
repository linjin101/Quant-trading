package user.demo.base;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.IOException;
import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;

/**
 * 所有项目模块中处理请求的父类
 */
public abstract class BaseServlet extends HttpServlet {

    /**
     * http://www.cnblogs.com/caoleiCoding/p/8125885.html
     * @param req
     * @param resp
     * @throws ServletException
     * @throws IOException
     *
     * Servlet方法之service()
     * 1、service一定要用吗？作用是什么？是不是在程序开始运行时，自动装载执行的系统方法（类似于main）？
     *
     * 　　Service是类GenericServlet中最重要的方法，每次客户向服务器发出请求时，服务器就会调用这个方法。
     * 程序员如果想对客户的请求进行响应的话就必须覆盖这个方法，并在这个方法中加入自己的代码来实现对客户的响应。
     * Service有两个参数（ServletRequest和ServletResponse），ServletRequest保存了客户向服务器发送的请求，而ServletResponse用来设置如何对客户进行响应。
     *
     * 有一个问题我们应当注意：就是init()方法（Servlet的两个生命周期函数之一，另一个是destroy()）和service()方法的区别，
     * 当我们改变源程序而重新生成一个新的.class文件的时候，此时如果再次执行该Servlet，会发现执行的结果跟没做更改的时候一样，
     * 原因就是因为相同的Servlet的init只执行一次，所以当我们在调试Servlet的时候要不断改变文件名和类名，或者重新启动服务（Tomcat或者JWS等）。
     * 就是说，init()方法仅在服务器装载Servlet时才由服务器执行一次，而每次客户向服务器发请求时，服务器就会调用Service()方法。
     *
     *
     *
     * 2、在建立一个继承了HttpServlet类并重写了该类的service()、doPost()和doGet()方法时，java会如何执行？
     *
     * 其实若是这三个方法都在存在的情况下，java只会执行service()方法，而其他的两种方法不会被执行。若是没有service() 方法，
     * 则是根据jsp传入方式选择对应的方法。比如说，若是jsp是以Post方式传入数据，则是调用doPost()方法处理数据。
     * 但是一般上在建立一个继承HttpServlet类时都会重写doPost()和doGet()方法，而且会在其中一个方法中处理数据，
     * 另一个方法则是直接调用该方法
     */
    @Override
    protected void service(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {

        System.out.println(this);
        System.out.println(req.getRequestURL());
        System.out.println(req.getRequestURI());
        System.out.println(req.getContextPath());

        String requestURI = req.getRequestURI();
        if (!requestURI.contains(".jsp") && !requestURI.contains(".css") && !requestURI.contains(".js")) {

            // 获取调用的方法名称
            int iSub = requestURI.lastIndexOf("/" )+1;
            String methodName = requestURI.substring( iSub );

            Class<? extends BaseServlet> cls = this.getClass();
            Method method = null;
            //通过类对象创建一个方法对象
            Method[] methods = cls.getDeclaredMethods();
            for (Method m : methods) {
                if (methodName.equals(m.getName())) {
                    method = m;
                    break;
                }
            }
            if (method.equals(null)) {
                throw new RuntimeException(cls + "类中没有该方法" + methodName);
            }

            try {
                Object result = method.invoke(this);

                if (result != null) {
                    if (result.toString().startsWith("redirect:")) {
                        //重定向
                        result = result.toString().replaceAll("redirect:", "");
                        /**
                         *     今天写Servlet使用跳转：
                         *
                         * response.sendRedirect("main.jsp");
                         *
                         * request.getRequestDispatcher("main.jsp").forward(request, response);
                         *
                         * 这两种跳转方式（内跳、外跳）运行时都报错，如下：(网页上报HTTP Status 405错误)

                         *
                         *     检查了一遍程序逻辑，发现应该无误，而且之前写的Servlet也从未报错，
                         * 最后知道了报错原因，记录如下：
                         *
                         * 大致三种解决方法：
                         *
                         *     （1）Servlet要重写doGet()、doPost()方法，网上说是只重写doGet方法，
                         * 而不重写doPost方法的话，是会报这个错的，但是我不是这个原因；
                         *
                         *     （2）在跳转之后要加上return这条语句，这在一些书籍上也是可以找到的，因为跳转以后，下
                         * 面的代码已经完全无用，只会产生影响，所以加上return语句可以防止产生错误，我也不是这个原因。
                         *
                         *     （3）删除重写的doGet()、doPost()这两个方法里面的super.doGet()和super.doPost()语句。因为如果使用eclipse开发，
                         * 使用eclipse来自动生成重写方法的话，默认是会去调用父类的doGet()、doPost()方法的，我之前开发的时候，都把这两句删掉的，今
                         * 天可能操作不当，没删掉，所以导致报错，这种原因导致报错的话，就删掉父类方法调用那两句代码就行了。
                         *
                         */
                        resp.sendRedirect(result + "");
                        return;
                    } else {
                        req.getRequestDispatcher(result.toString()).forward(req, resp);
                    }
                }
            } catch (IllegalAccessException e) {
                e.printStackTrace();
            } catch (InvocationTargetException e) {
                e.printStackTrace();
            }
            super.service(req, resp);
        }
    }

}
