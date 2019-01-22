<?php
		$BOT['TOKEN']	= "615379969:AAG7-0j21A09cCR4vbCG0aMjrVLLo9LPWxY";
		$BOT['URL']		= "https://api.telegram.org/bot".$BOT['TOKEN'];

		function performCurl($function, $params)
		{
			global $BOT;

			$ch = curl_init($BOT['URL'] . $function);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_exec($ch);
			curl_close($ch);
		}

		$data = json_decode(file_get_contents("php://input"));

		if (isset($data->message->chat->type) && $data->message->chat->type != "private")
		{
			foreach ($data->message->new_chat_members as $user)
			{
				$params = [
					"chat_id"	=> $data->message->chat->id,
					"user_id"	=> $user->id
				];
				performCurl("/restrictChatMember", $params);
			}
		}
		else if (isset($data->message->text))
		{
			$text = "<b>Group Mute Bot</b>\n\nMute new users in groups forever.\n\nHow to start ?\n1) Invite @Group_MuteBot to your group\n2) Promote @Group_MuteBot to Admin\n\nSource: bit.ly/2FQVyAd";

			$params = [
				"chat_id"		=> $data->message->chat->id,
				"text"			=> $text,
				"parse_mode"	=> "HTML"
			];

			performCurl("/sendMessage", $params);
		}
?>