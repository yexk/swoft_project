package main

import (
	"bytes"
	"fmt"
	"io"
	"log"
	"net/http"
	"strconv"
	"time"

	"github.com/gin-gonic/gin"
	consulapi "github.com/hashicorp/consul/api"
)

func main() {
	r := gin.Default()

	r.GET("/", func(c *gin.Context) {
		c.JSON(200, gin.H{
			"message": "Ok",
		})
	})

	r.GET("/aaaa", func(c *gin.Context) {
		c.JSON(200, gin.H{
			"message": "Ok",
		})
	})

	// 注册服务到consul
	ConsulRegister()

	// 从consul中发现服务
	ConsulFindServer()

	ConsulKVTest()
	// 取消consul注册的服务
	//ConsulDeRegister()

	http.ListenAndServe(":8083", r)

}

// ConsulRegister 注册服务到consul
func ConsulRegister() {
	// 创建连接consul服务配置
	config := consulapi.DefaultConfig()
	config.Address = "consul:8500"
	client, err := consulapi.NewClient(config)
	if err != nil {
		log.Fatal("consul client error : ", err)
	}

	// 创建注册到consul的服务到
	registration := new(consulapi.AgentServiceRegistration)
	registration.ID = "333"
	registration.Name = "go-consul-test"
	registration.Port = 8083
	registration.Tags = []string{"go-consul-test"}
	registration.Address = "go"

	// 增加consul健康检查回调函数
	check := new(consulapi.AgentServiceCheck)
	check.HTTP = fmt.Sprintf("http://%s:%d", registration.Address, registration.Port)
	check.Timeout = "5s"
	check.Interval = "5s"
	check.DeregisterCriticalServiceAfter = "30s" // 故障检查失败30s后 consul自动将注册服务删除
	registration.Check = check

	// 注册服务到consul
	err = client.Agent().ServiceRegister(registration)
}

// ConsulDeRegister 取消consul注册的服务
func ConsulDeRegister() {
	// 创建连接consul服务配置
	config := consulapi.DefaultConfig()
	config.Address = "consul:8500"
	client, err := consulapi.NewClient(config)
	if err != nil {
		log.Fatal("consul client error : ", err)
	}

	client.Agent().ServiceDeregister("111")
}

// ConsulFindServer 从consul中发现服务
func ConsulFindServer() {
	// 创建连接consul服务配置
	config := consulapi.DefaultConfig()
	config.Address = "consul:8500"
	client, err := consulapi.NewClient(config)
	if err != nil {
		log.Fatal("consul client error : ", err)
	}

	// 获取所有service
	services, _ := client.Agent().Services()
	for _, value := range services {
		fmt.Println(value.Address)
		fmt.Println(value.Port)
	}

	fmt.Println("=================================")
	// 获取指定service
	service, _, err := client.Agent().Service("swoft", nil)
	if err == nil {
		fmt.Println(service.Address)
		fmt.Println(service.Port)
	} else {
		return
	}

	url := "http://" + service.Address + ":" + strconv.Itoa(service.Port) + "/api/getbaiduyun"
	response := Get(url)
	fmt.Println(response)
	fmt.Println("=================================")
}

// Get get
func Get(url string) string {

	// 超时时间：5秒
	client := &http.Client{Timeout: 5 * time.Second}
	resp, err := client.Get(url)
	if err != nil {
		panic(err)
	}
	defer resp.Body.Close()
	var buffer [512]byte
	result := bytes.NewBuffer(nil)
	for {
		n, err := resp.Body.Read(buffer[0:])
		result.Write(buffer[0:n])
		if err != nil && err == io.EOF {
			break
		} else if err != nil {
			panic(err)
		}
	}

	return result.String()
}

// ConsulCheckHeath 链接配置
func ConsulCheckHeath() {
	// 创建连接consul服务配置
	config := consulapi.DefaultConfig()
	config.Address = "consul:8500"
	client, err := consulapi.NewClient(config)
	if err != nil {
		log.Fatal("consul client error : ", err)
	}

	// 健康检查
	a, b, _ := client.Agent().AgentHealthServiceByID("111")
	fmt.Println(a)
	fmt.Println(b)
}

// ConsulKVTest test
func ConsulKVTest() {
	// 创建连接consul服务配置
	config := consulapi.DefaultConfig()
	config.Address = "consul:8500"
	client, err := consulapi.NewClient(config)
	if err != nil {
		log.Fatal("consul client error : ", err)
	}

	// KV, put值
	values := "test"
	key := "go-consul-test/consul:8100"
	client.KV().Put(&consulapi.KVPair{Key: key, Flags: 0, Value: []byte(values)}, nil)

	// KV get值
	data, _, _ := client.KV().Get(key, nil)
	fmt.Println(string(data.Value))

	// KV list
	datas, _, _ := client.KV().List("go", nil)
	for _, value := range datas {
		fmt.Println(value)
	}
	keys, _, _ := client.KV().Keys("go", "", nil)
	fmt.Println(keys)
}
