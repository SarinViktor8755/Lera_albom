package main

import (
	"fmt"
	"log"
	"net/http"
	"os"
)

func main() {
	// Создаем необходимые папки
	if err := createFolders(); err != nil {
		log.Fatal(err)
	}

	// Настройка обработчиков
	http.HandleFunc("/", serveHomePage)
	http.Handle("/static/", http.StripPrefix("/static/", http.FileServer(http.Dir("static"))))
	http.Handle("/img/", http.StripPrefix("/img/", http.FileServer(http.Dir("img"))))
	http.Handle("/js/", http.StripPrefix("/js/", http.FileServer(http.Dir("js"))))

	// Запуск сервера
	port := ":8080"
	fmt.Printf("Сервер запущен на http://localhost%s\n", port)
	log.Fatal(http.ListenAndServe(port, nil))
}

func serveHomePage(w http.ResponseWriter, r *http.Request) {
	if r.URL.Path != "/" {
		http.NotFound(w, r)
		return
	}

	htmlFile, err := os.ReadFile("index.html")
	if err != nil {
		http.Error(w, "Не удалось загрузить страницу", http.StatusInternalServerError)
		return
	}

	w.Header().Set("Content-Type", "text/html; charset=utf-8")
	w.Write(htmlFile)
}

func createFolders() error {
	requiredFolders := []string{"img", "js", "static"}
	for _, folder := range requiredFolders {
		if err := os.MkdirAll(folder, 0755); err != nil {
			return fmt.Errorf("не удалось создать папку %s: %v", folder, err)
		}
	}
	return nil
}