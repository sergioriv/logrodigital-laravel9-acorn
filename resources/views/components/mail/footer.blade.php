<table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation"
    style="box-sizing: border-box;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
        position: relative;
        -premailer-cellpadding: 0;
        -premailer-cellspacing: 0;
        -premailer-width: 570px;
        margin: 0 auto;
        padding: 0;
        text-align: center;
        width: 570px;"
>
    <tbody>
        <tr>
            <td class="content-cell" align="center"
                style="box-sizing: border-box;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
                    position: relative;
                    max-width: 100vw;
                    padding: 32px;"
            >
                <p
                    style="box-sizing: border-box;
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
                        position: relative;
                        line-height: 1.5em;
                        margin-top: 0;
                        color: #b0adc5;
                        font-size: 12px;
                        text-align: center;"
                >
                    {{ Illuminate\Mail\Markdown::parse($slot) }}
                </p>
            </td>
        </tr>
    </tbody>
</table>
