package user.demo.user;
import user.demo.base.BaseServlet;
import javax.servlet.annotation.WebServlet;

/**
 * <pre>
 *     http://localhost:8090/rootPath/methodName
 * </pre>
 */
@WebServlet(name="UserController" , urlPatterns = "/user/*")
public class UserController extends BaseServlet {

    public String index(){
//        System.out.println("/WEB-INF/user/index.jsp");
        return "/WEB-INF/user/index.jsp";
    }

    public String initAdd(){
//        System.out.println("/WEB-INF/user/add.jsp");// 转发
        return "/WEB-INF/user/add.jsp";
    }

    public String addUser(){
//        System.out.println("redirect:/user/index");  //重定向
        return "redirect:/user/initAdd";
    }
}
