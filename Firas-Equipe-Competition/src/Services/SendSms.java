package Services;
import com.twilio.Twilio; 
import com.twilio.rest.api.v2010.account.Message; 
 

public class SendSms {
    // Find your Account SID and Auth Token at twilio.com/console
    // and set the environment variables. See http://twil.io/secure
  

 public static void send(String num,String msg) {
        String ACCOUNT_SID = "AC9c6d54b386b07326b4a4d5384fbad20b";
        String AUTH_TOKEN = "e62a77d83e66583f5bb4ab6b9a7b16c9";
        Twilio.init(ACCOUNT_SID, AUTH_TOKEN);// cnx m3a serveur api
        Message message = Message.creator(
                new com.twilio.type.PhoneNumber(num),
                new com.twilio.type.PhoneNumber("+16813233437"),
                msg)
            .create();

        System.out.println(message.getSid());
    }
}