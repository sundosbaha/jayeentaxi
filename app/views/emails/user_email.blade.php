<html>
    <head>
        <title>Invoice</title>
    </head>
    <body style="margin: 0px; padding: 0px; border: 0px;font-family: sans-serif;"><?php $date=date('m-d-Y');
    $pre=date('Y-m-d', strtotime('-1 day', strtotime($date))) ?>

    <h3>User Report - <?php echo  $pre;?></h3>
    <table>
        <tr>
            <th>
               {{ trans('email_translator.fname') }}
            </th>
            <th>
                {{ trans('email_translator.lname') }}
            </th>
            <th>
                {{ trans('email_translator.email') }}
            </th>
            <th>
                {{ trans('email_translator.phone') }}
            </th>
        </tr>

        <?php
            foreach($user as $u)

                {
        ?>
        <tr>
            <td>
                <?php echo $u->first_name; ?>
            </td>
            <td>
                <?php echo $u->last_name; ?>
            </td>
            <th>
                <?php echo $u->email; ?>
            </th>
            <th>
                <?php echo $u->phone; ?>
            </th>
        </tr>
        <?php
        }
        ?>

    </table>
    </body>
</html>
