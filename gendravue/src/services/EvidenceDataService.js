import http from "../http-common";

class EvidenceDataService {
  getAll() {
    return http.get("/evidenciasindex");
  }

  get(id) {
    return http.get(`/evidencia/${id}`);
  }

  create(data) {
    return http.post("/evidencestore", data);
  }

  update(id, data) {
    return http.put(`/updateevidence/${id}`, data);
  }

  delete(id) {
    return http.delete(`/deleteevidence/${id}`);
  }

  deleteAll() {
    return http.delete(`/deleteevidence`);
  }

  findByTitle(title) {
    return http.get(`/evidencias?title=${title}`);
  }
}

export default new EvidenceDataService();
