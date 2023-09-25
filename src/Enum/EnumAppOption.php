<?php

namespace App\Enum;

class EnumAppOption
{
    /** Время начала актуальной темы [название_опции_userId]*/
    const CHAT_THEME_START_TIME = "chat_theme_start_time";

    /** Ид сообщений вводимых пользователем в чат [название_опции_userId]*/
    const CHAT_USER_MESSAGE_ID_LIST = "chat_user_message_id_list";

    /** Флаг говорящий о том, что обработчик gpt подготавливает ответ на вопрос [название_опции_userId]*/
    const CHAT_USER_GPT_HANDLER_IS_ACTIVE = "chat_user_gpt_handler_is_active";

    /** Время когда запустился обработчик для подготовки ответа на вопрос gpt [название_опции_userId]*/
    const CHAT_USER_GPT_HANDLER_ACTIVE_EXPIRED_TIME = "chat_user_gpt_handler_active_expired_time";

    /** Режим, какой использовать токен для запроса к gpt чату*/
    const SERVICE_TOKEN_MODE = "service_token_mode";

    /** Токен пользователя */
    const SERVICE_USER_TOKEN = "service_user_token";

}