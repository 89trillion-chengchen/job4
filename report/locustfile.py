import time
from locust import HttpUser, task, between

class QuickstartUser(HttpUser):
    wait_time = between(1, 2.5)


    @task
    def req_getCombatPoints(self):
        data = {
            "uid": "1"
        }
        login_response = self.client.post('/Login/login', data=data)

    @task
    def req_getLegalCvcAndUnlockedSoldier(self):
            data = {
               "uid": "1",
               "role": "1",
               "code": "code_O0afeWfR"
            }
            login_response = self.client.post('/Login/useCode', data=data)



