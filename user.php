<?php

class CartController extends Controller
{
    public function updateUsers($users)
    {
        foreach ($users as $user) {
            try {
                if (!empty($user['name']) && !empty($user['login']) && $this->isValidEmail($user['email']) && !empty($user['password']) && strlen($user['name']) >= 10){
                    DB::table('users')->where('id', $user['id'])->update([
                        'name' => $user['name'],
                        'login' => $user['login'],
                        'email' => $user['email'],
                        'password' => md5($user['password'])
                    ]);
                }
                else{
                    throw new Exception('Name:'. $user['name']. ' Login: '. $user['login'] . ' Email:'. $user['email']. ' Password:'. $user['password']. '\n');
                }
            } catch (\Throwable $e) {
                return Redirect::back()->withErrors(['error', ['We couldn\'t update user: ' . $e->getMessage()]]);
            }
        }
        return Redirect::back()->with(['success', 'All users updated.']);
    }

    public function storeUsers($users)
    {

        foreach ($users as $user) {
            try {
                if (!empty($user['name']) && !empty($user['login']) && $this->isValidEmail($user['email']) && !empty($user['password']) && strlen($user['name']) >= 10) {
                    DB::table('users')->insert([
                        'name' => $user['name'],
                        'login' => $user['login'],
                        'email' => $user['email'],
                        'password' => md5($user['password'])
                    ]);
                }
                else{
                    throw new Exception('Name:'. $user['name']. ' Login: '. $user['login'] . ' Email:'. $user['email']. ' Password:'. $user['password']. '\n');
                }
            } catch (\Throwable $e) {
                return Redirect::back()->withErrors(['error', ['We couldn\'t store user: ' . $e->getMessage()]]);
            }
        }
        $this->sendEmail($users);
        return Redirect::back()->with(['success', 'All users created.']);
    }

    private function sendEmail($users)
    {
        foreach ($users as $user) {
            $message = 'Account has beed created. You can log in as <b>' . $user['login'] . '</b>';
            if ($user['email']) {
                Mail::to($user['email'])
                    ->cc('support@company.com')
                    ->subject('New account created')
                    ->queue($message);
            }
        }
        return true;
    }

    private function isValidEmail($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

}
