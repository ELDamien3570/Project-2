<?php
    require 'api.php';
    $companies = null;
    $foundCompany = null;
    $notFound = false;
    $message = null;

    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        $name = trim(filter_input(INPUT_POST, 'name') ?? '');
        $location = trim(filter_input(INPUT_POST, 'location') ?? '');

        if ($action === 'get_all') {
            $companies = getAllCompanies();

        } 
        elseif ($action === 'get_name') {
            
            $foundCompany = getCompanyByName($name);
            if ($foundCompany === null) {
                $notFound = true;
            }

        } 
        elseif ($action === 'post') {
            
            $result = createCompany($name, $location);

            if (isset($result['message']))
            {
                $message = $result['message'];
            }
            else
            {
                $message = 'Something went wrong.';
            }
        } 
        elseif ($action === 'put') {
            $result = updateCompany($name, $location);

            if (isset($result['message']))
            {
                $message = $result['message'];
            }
            else
            {
                $message = 'Something went wrong.';
            }
        } 
        elseif ($action === 'delete') {
            
            $result = deleteCompany($name);
            
            if (isset($result['message']))
            {
                $message = $result['message'];
            }
            else
            {
                $message = 'Something went wrong.';
            }
        }
    }
?>

<!DOCTYPE html>
<html>  
    <head>
        <meta charset="UTF-8">
        <title>Company Database Front-End</title>
    </head>
    <body>       
        <header>
            <h1>Company Database Front-End</h1>
        </header>
        <form method="POST">
            <label>Name: <input type="text" name="name"></label></br>
            <label>Location: <input type="text" name="location"></label></br>
            </br>
            <button type="submit" name="action" value="get_all">Get All</button>
            <button type="submit" name="action" value="get_name">Get by Name</button>
            <button type="submit" name="action" value="post">Create Company</button>
            <button type="submit" name="action" value="put">Update Company Location</button>
            <button type="submit" name="action" value="delete">Delete Company</button>                    
        </form>
        
        <?php if ($message): ?>
            <p><?= htmlspecialchars($message)?></p>
        <?php endif; ?>
        
        <?php if ($notFound): ?>
            <p>No company with that name.</p>
        <?php endif; ?>
 
        <?php if ($foundCompany): ?>
            <p>
                <b><?= htmlspecialchars($foundCompany['name']) ?> </b> | <?= htmlspecialchars($foundCompany['location'] ?? '') ?>
            </p>
        <?php endif; ?>
 
        <?php if ($companies !== null): ?>
            <h1>~~~~ Company Name | Location ~~~~</h1>
            <ul>
                <?php foreach ($companies as $company): ?>
                    <li>
                        <b><?= htmlspecialchars($company['name']) ?></b> | <?= htmlspecialchars($company['location'] ?? '') ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
            
    </body>
</html>